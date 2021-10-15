<?php
namespace Test\TestovaciRepositoryTest;

use PHPUnit\Framework\TestCase;

use Model\Hydrator\NameHydrator\AccessorMethodNameHydrator;
use Model\Hydrator\Filter\OneToOneFilter;
use Model\Hydrator\OneToOneAccessorHydrator;

use Model\Testovaci\Entity\TestovaciEntity;
use Model\Testovaci\Identity\TestovaciIdentity;
use Model\Testovaci\RowObjectManager\TestovaciRowObjectManager;
use Model\Testovaci\Repository\TestovaciRepositoryReadOnly;
use Model\Testovaci\Repository\TestovaciRepository;

use Model\Repository\Exception\OperationWithLockedEntityException;
use Model\Repository\Exception\UnpersistedEntityInCollectionException;
use Model\Repository\Exception\UnableWriteToReadOnlyRepoException;


/**
 * Description of TestovaciRepositoryTestA
 *
 * @author vlse2610
 */
class TestovaciRepositoryTest  extends TestCase {
    /**
     * 
     */
   private static $rowObjectManager ;
    
   private $testovaciRepository;
   private $testovaciRepositoryReadOnly;
   
   private $identity1;
   private $entity1 ;
    
    
    
    public static function setUpBeforeClass(): void {
        self::$rowObjectManager = new TestovaciRowObjectManager();
        
    }
    
    
    protected function setUp(): void {
        $methodNameHydratorEntity = new AccessorMethodNameHydrator();
        $methodNameHydratorIdentity = new AccessorMethodNameHydrator();
        $poleJmenIdentity =   [ 
            "id1", "id2",];
        $poleJmenEntity =  [             
            "prvekVarchar", "prvekDatetime", "celeJmeno"];
//            "prvekChar" , "prvekVarchar", "prvekInteger" ,"prvekText", "prvekBoolean",  
//            "prvekDate", "prvekDatetime", "prvekTimestamp" , 'jmenoClovek', 'prijmeniClovek' 
//            ] ; 
        $filterEntity = new OneToOneFilter( $poleJmenEntity);
        $filterIdentity = new OneToOneFilter( $poleJmenIdentity);
        
        $accessorHydratorEntity = new OneToOneAccessorHydrator($methodNameHydratorEntity, $filterEntity) ;
        $accessorHydratorIdentity = new OneToOneAccessorHydrator($methodNameHydratorIdentity, $filterIdentity) ;

        $this->testovaciRepository = new TestovaciRepository( $accessorHydratorEntity, $accessorHydratorIdentity,  self::$rowObjectManager );           
        
        $this->identity1 = new TestovaciIdentity(); 
            $this->identity1->setId1('66');         
            $this->identity1->setId2('33') ;
        $this->entity1 = new TestovaciEntity( $this->identity1 );              
            $this->entity1->setCeleJmeno("Jméno Celé"); 
            $this->entity1->setPrvekVarchar('') ;
            $this->entity1->setPrvekDatetime(new \DateTime('2000-01-01')) ;                                
        //--------------------------------------------------
            
        $this->testovaciRepositoryReadOnly = new TestovaciRepositoryReadOnly(
                        $accessorHydratorEntity, $accessorHydratorIdentity,  self::$rowObjectManager );           
    
    }
    
    
    
    
    public function testAdd() {               
        $this->testovaciRepository->add($this->entity1);
        $this->assertFalse($this->entity1->isPersisted() );
       // $this->assertTrue($entity1->isLocked() ); //lock netestovat
          
        $this->expectException( OperationWithLockedEntityException::class );
        $this->testovaciRepository->add($this->entity1);
                
        $this->testovaciRepository->flush();          
        $this->assertTrue($this->entity1->isPersisted() );
       // $this->assertFalse($entity1->isLocked() );  //lock netestovat    
    }
    
    
    
    public function test_VCollectionNepersistovanaEntita() {                           
        $entity2 = $this->testovaciRepository->get($this->identity1);
        $this->assertNotNull($entity2);
        
        $entity2->setUnpersisted();
        
        try {
            $this->testovaciRepository->flush(); 
        }
        catch ( \Exception $e) {
            $entity2->setPersisted(); //vracim persisted
            $this->expectException( UnpersistedEntityInCollectionException::class );
            throw $e;
        }        
     }
    
    
    
    
    
    
    
    
    public function testGet() {               
        $entity2 = $this->testovaciRepository->get($this->identity1);
        
        $this->assertContainsOnlyInstancesOf( TestovaciEntity::class, [$entity2] );              
        $this->assertInstanceOf(TestovaciEntity::class, $entity2);  
        
        $this->assertTrue($entity2->isPersisted() );
        //$this->assertFalse($entity2->isLocked() );   //lock netestovat
        $this->assertEquals( "Jméno Celé" , $entity2->getCeleJmeno());
        $e2Hodnota = $entity2->getPrvekDatetime();
        $this->assertEquals ( '01-01-2000', $e2Hodnota->format( "d-m-Y" ) );
        $this->assertEquals( "" ,  $entity2->getPrvekVarchar());                            
        
        /** @var TestovaciIdentity $identity2 */
        $this->assertEquals("66", $entity2->getIdentity()->getId1() );
        $this->assertEquals("33", $entity2->getIdentity()->getId2() );
        //-----------------------------------------------------        
        
        // takova entita v repository neni
        $identity21 = new TestovaciIdentity(); 
           $identity21->setId1('66');         
           $identity21->setId2('31') ;   
        $entity3 = $this->testovaciRepository->get($identity21);   
        $this->assertIsNotObject($entity3);
        $this->assertNull($entity3);      
        
        1;
    }
    
    
    
    
    
    
    public function testRemove() {                   
        $entity2 = $this->testovaciRepository->get($this->identity1);
        $this->assertContainsOnlyInstancesOf( TestovaciEntity::class, [$entity2] );              
        $this->assertInstanceOf(TestovaciEntity::class, $entity2);  
           
        $this->testovaciRepository->remove($entity2);    
        $this->testovaciRepository->flush();        
        
        $entity3 = $this->testovaciRepository->get($this->identity1);
        $this->assertNull($entity3);   
    }
    
    
           
     
     
    public function test_NelzeMazatPravePridanouEntitu() {          
        $this->testovaciRepository->add($this->entity1);  //entity1 do ->new[]
        
        $this->expectException( OperationWithLockedEntityException::class );
        $this->testovaciRepository->remove($this->entity1);
    }
    
    
    
    public function test_NelzeMazatPraveSmazanouEntitu() {  
        $this->testovaciRepository->add($this->entity1);
        $this->testovaciRepository->flush();

        $this->testovaciRepository->remove($this->entity1);   // entity1 do ->removed[]
        
        $this->expectException( OperationWithLockedEntityException::class ); 
        $this->testovaciRepository->remove($this->entity1);                
    }
    
    //------------------------------------------------------------------------------
    public function test_NelzeZapisovatDoReadOnlyRepository() {          
       // $this->expectException( UnableWriteToReadOnlyRepoException::class );               
        try {            
            $this->testovaciRepositoryReadOnly->add($this->entity1);
        }
        catch ( \Exception $e) {
            $this->expectException( UnableWriteToReadOnlyRepoException::class );
            throw $e;
        }                           
    }
    
    public function test_NelzeMazatZReadOnlyRepository() {          
       // $this->expectException( UnableWriteToReadOnlyRepoException::class );               
        try {            
            $this->testovaciRepositoryReadOnly->remove($this->entity1);
        }
        catch ( \Exception $e) {
            $this->expectException( UnableWriteToReadOnlyRepoException::class );
            throw $e;
        }                           
    }    
    
    
    protected function tearDown(): void
    {              
            $this->testovaciRepository->__destruct();
    }
    
    
}
