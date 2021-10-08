<?php
namespace Test\TestovaciRepositoryTest;

use PHPUnit\Framework\TestCase;
use Model\Testovaci\RowObjectManager\TestovaciRowObjectManager;
use Model\Testovaci\Repository\TestovaciRepository;
use Model\Testovaci\Entity\TestovaciEntity;
use Model\Testovaci\Identity\TestovaciIdentity;
use Model\Hydrator\NameHydrator\AccessorMethodNameHydrator;
use Model\Hydrator\Filter\OneToOneFilter;
use Model\Hydrator\OneToOneAccessorHydrator;

use Model\Repository\Exception\OperationWithLockedEntityException;
use Model\Repository\Exception\UnpersistedEntityInCollectionException;

/**
 * Description of TestovaciRepositoryTestA
 *
 * @author vlse2610
 */
class TestovaciRepositoryTestA  extends TestCase {
    /**
     * 
     */
   private static $rowObjectManager ;
    
   private  $testovaciRepository;
    
    
    
    
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
              
    }
    
    
    
    
    public function testAdd() { 
        $identity1 = new TestovaciIdentity(); 
            $identity1->setId1('66');         
            $identity1->setId2('33') ;
        $entity1 = new TestovaciEntity( $identity1 );              
            $entity1->setCeleJmeno("Jméno Celé"); 
            $entity1->setPrvekVarchar('') ;
            $entity1->setPrvekDatetime(new \DateTime('2000-01-01')) ;                              
            
        $this->testovaciRepository->add($entity1);
        $this->assertFalse($entity1->isPersisted() );
       // $this->assertTrue($entity1->isLocked() ); //lock netestovat
          
        $this->expectException( OperationWithLockedEntityException::class );
        $this->testovaciRepository->add($entity1);
                
        $this->testovaciRepository->flush();          
        $this->assertTrue($entity1->isPersisted() );
       // $this->assertFalse($entity1->isLocked() );  //lock netestovat
        
            
    
    }
    
    
    
    
    public function testGet() { 
        $identity1 = new TestovaciIdentity(); 
           $identity1->setId1('66');         
           $identity1->setId2('33') ;                
    
        $entity2 = $this->testovaciRepository->get($identity1);
        
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
        $identity2 = new TestovaciIdentity(); 
           $identity2->setId1('66');         
           $identity2->setId2('31') ;   
        $entity3 = $this->testovaciRepository->get($identity2);   
        $this->assertIsNotObject($entity3);
        $this->assertNull($entity3);
        
    }
    
    
    
    public function testRemove() { 
        $identity1 = new TestovaciIdentity(); 
           $identity1->setId1('66');         
           $identity1->setId2('33') ;
        $entity1 = new TestovaciEntity( $identity1 );              
           $entity1->setCeleJmeno("Jméno Celé"); 
           $entity1->setPrvekVarchar('') ;
           $entity1->setPrvekDatetime(new \DateTime('2000-01-01')) ;      
          
           
       
        $entity2 = $this->testovaciRepository->get($identity1);
        $this->assertContainsOnlyInstancesOf( TestovaciEntity::class, [$entity2] );              
        $this->assertInstanceOf(TestovaciEntity::class, $entity2);  
           
        $this->testovaciRepository->remove($entity2);    
        $this->testovaciRepository->flush();
        
        
        $entity3 = $this->testovaciRepository->get($identity1);
        $this->assertNull($entity3);
           
           
    }
    
    
    
     public function testVCollectionNepersistovanaEntita() { 
        //"V collection je nepersistovaná entita.")
        $identity1 = new TestovaciIdentity(); 
            $identity1->setId1('66');         
            $identity1->setId2('44') ;
        $entity1 = new TestovaciEntity( $identity1 );              
            $entity1->setCeleJmeno("Jméno CeléDruhé"); 
            $entity1->setPrvekVarchar('') ;
            $entity1->setPrvekDatetime(new \DateTime('2000-01-01')) ;                              
            
            
        $this->testovaciRepository->add($entity1);
        $this->testovaciRepository->flush(); 
        
        $entity2 = $this->testovaciRepository->get($identity1);
        $this->assertNotNull($entity2);
        
        $entity2->setUnpersisted();
        
        $this->expectException( UnpersistedEntityInCollectionException::class );
        $this->testovaciRepository->flush(); 

        // $entity2->setPersisted();
       

     }
    
    
    
    protected function tearDown(): void
    {
        $this->testovaciRepository->__destruct();
    }
    
    
}
