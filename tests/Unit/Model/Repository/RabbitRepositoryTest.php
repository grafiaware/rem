<?php
namespace Test\TestovaciRepositoryTest;

use PHPUnit\Framework\TestCase;

use Model\Hydrator\NameHydrator\AccessorMethodNameHydrator;
use Model\Filter\OneToOneFilter;
use Model\Hydrator\OneToOneAccessorHydrator;

use Model\RowObjectManager\RowObjectManagerInterface;
use Model\RowObjectManager\RowObjectManager;

use Model\Testovaci\Identity\RabbitIdentity;
use Model\Testovaci\Identity\KlicIdentity;
use Model\Testovaci\Identity\CarrotIdentity;
use Model\Testovaci\Identity\RabbitIdentityInterface;
use Model\Testovaci\Identity\KlicIdentityInterface;
use Model\Testovaci\Identity\CarrotIdentityInterface;

use Model\Entity\Identities;
use Model\Testovaci\Entity\Enum\RabbitIdentityNamesEnum;
use Model\Testovaci\Entity\Enum\CarrotIdentityNamesEnum;
use Model\Testovaci\Entity\RabbitEntity;

use Model\Testovaci\Repository\RabbitRepositoryReadOnly;
use Model\Testovaci\Repository\RabbitRepository;
use Model\Testovaci\Repository\CarrotRepository;

use Model\Repository\Exception\OperationWithLockedEntityException;
use Model\Repository\Exception\UnpersistedEntityInCollectionException;
use Model\Repository\Exception\UnableWriteToReadOnlyRepoException;

use Model\IdentityMap\IdentityMap;
use Model\IdentityMap\IndexMaker\IndexMaker;


/**
 * Description of TestovaciRepositoryTestA
 *
 * @author vlse2610
 */
class RabbitRepositoryTest  extends TestCase {   
   /**
    *
    * @var RowObjectManagerInterface
    */  
   static private $rowObjectRabbittManager ;
   /**
    * 
    * @var RowObjectManagerInterface 
    */
   static private $rowObjectCarrotManager ;
     
    
   private $rabbitRepository;
   private $rabbitRepositoryReadOnly;
         
   private $rabbitIdentity;
   private $klicIdentity;
   
   private $rabbitEntity1 ;
       
   private $carrotRepository;
   
 
   
   
    
    public static function setUpBeforeClass(): void {
       self::$rowObjectRabbittManager = new RowObjectManager();
       self::$rowObjectCarrotManager = new RowObjectManager();           
    }
    
    
    protected function setUp(): void {
        $methodNameHydratorEntity = new AccessorMethodNameHydrator();
        $methodNameHydratorIdentity = new AccessorMethodNameHydrator();
        $poleJmenIdentityRabbit =   [ 
            "id1", "id2"];
        $poleJmenEntityRabbit =  [             
            "prvekVarchar", "prvekDatetime", "celeJmeno"];
//            "prvekChar" , "prvekVarchar", "prvekInteger" ,"prvekText", "prvekBoolean",  
//            "prvekDate", "prvekDatetime", "prvekTimestamp" , 'jmenoClovek', 'prijmeniClovek' 
//            ] ; 
        $filterEntity = new OneToOneFilter( $poleJmenEntityRabbit);
        $filterIdentity = new OneToOneFilter( $poleJmenIdentityRabbit);        
        $filtersI [] = $filterIdentity;
        
        $accessorHydratorEntity = new OneToOneAccessorHydrator($methodNameHydratorEntity, $filterEntity) ;
        $accessorHydratorIdentityArray[RabbitIdentityInterface::class] = 
                [ new OneToOneAccessorHydrator($methodNameHydratorIdentity, $filterIdentity)  ];

        //$identityNames = new RabbitIdentityNamesEnum();
        $indexMaker = new IndexMaker( $methodNameHydratorEntity );
        $identityMap = new IdentityMap( $indexMaker, $filtersI );
        
        $rabbitIdentityNames = new RabbitIdentityNamesEnum();
        $carrotIdentityNames = new CarrotIdentityNamesEnum();
        
        $this->carrotRepository = new CarrotRepository( [$accessorHydratorEntity], 
                                                        $accessorHydratorIdentityArray, 
                                                        $identityMap,
                                                        self::$rowObjectCarrotManager
                                                        );                 
        $this->rabbitRepository = new RabbitRepository( [$accessorHydratorEntity], 
                                                        $accessorHydratorIdentityArray, 
                                                        $identityMap,
                                                        self::$rowObjectRabbittManager                                                        
                                                      );     
                    
        $this->rabbitIdentity = new RabbitIdentity(); 
        $this->rabbitIdentity->setId1('66');         
            //$this->rabbitIdentity->setId2('33');
        $this->klicIdentity = new KlicIdentity();
        $this->klicIdentity->setKlic('klicKralika');
        
        $identitiesA[ RabbitIdentityInterface::class ] = $this->rabbitIdentity;
        $identitiesA[ KlicIdentityInterface::class ] = $this->klicIdentity;    
        $rabbitIdentities = new Identities($rabbitIdentityNames, $identitiesA);         
        
        $this->rabbitEntity1 = new RabbitEntity( $rabbitIdentities );              
            $this->rabbitEntity1->setCeleJmeno("Jméno Celé"); 
            $this->rabbitEntity1->setPrvekVarchar('') ;
            $this->rabbitEntity1->setPrvekDatetime(new \DateTime('2000-01-01'));                                
        //--------------------------------------------------
            
//        $this->rabbitRepositoryReadOnly = new RabbitRepositoryReadOnly (
//                        $accessorHydratorEntity, $accessorHydratorIdentityArray,   self::$rowObjectRabbittManager, y  );               
      }
    
    
    
    
    public function testAddGet() {               
        $this->rabbitRepository->add($this->rabbitEntity1);
        $this->assertFalse($this->rabbitEntity1->isPersisted() ); //
       // $this->assertTrue($entity1->isLocked() ); //lock netestovat
          
        $this->expectException( OperationWithLockedEntityException::class ); //entita je zamknuta
        $this->rabbitRepository->add($this->rabbitEntity1);
                
        $this->rabbitRepository->flush();          
        $this->assertTrue($this->rabbitEntity1->isPersisted() );  //po flush je entita persistovana
       // $this->assertFalse($entity1->isLocked() );  //lock netestovat    
  
    //--------------------------------------------------------------------------
    
    
        $entity2 = $this->rabbitRepository->get($this->rabbitIdentity1);
       
        $this->assertContainsOnlyInstancesOf( RabbitEntity::class, [$entity2] );              
        $this->assertInstanceOf(RabbitEntity::class, $entity2);  
        
        $this->assertTrue($entity2->isPersisted() );
        //$this->assertFalse($entity2->isLocked() );   //lock netestovat
        $this->assertEquals( "Jméno Celé" , $entity2->getCeleJmeno());
        $e2Hodnota = $entity2->getPrvekDatetime();
        $this->assertEquals ( '01-01-2000', $e2Hodnota->format( "d-m-Y" ) );
        $this->assertEquals( "" ,  $entity2->getPrvekVarchar());                            
        
        /** @var RabbitIdentity $identity2 */
        $this->assertEquals("66", $entity2->getIdentity()->getId1() );
        $this->assertEquals("33", $entity2->getIdentity()->getId2() );
        //-----------------------------------------------------        
        
        // takova entita v repository neni
        $identity21 = new RabbitIdentity(); 
           $identity21->setId1('66');         
           $identity21->setId2('31') ;   
        $entity3 = $this->rabbitRepository->get($identity21);   
        $this->assertIsNotObject($entity3);
        $this->assertNull($entity3);      
    
    
    }  
    
    
    
//    
//    
//    public function test_VCollectionNepersistovanaEntita() {                           
//        $entity2 = $this->rabbitRepository->get($this->rabbitIdentity1);
//        $this->assertNotNull($entity2);
//        
//        $entity2->setUnpersisted();
//        
//        try {
//            $this->rabbitRepository->flush(); 
//        }
//        catch ( \Exception $e) {
//            $entity2->setPersisted(); //vracim persisted
//            $this->expectException( UnpersistedEntityInCollectionException::class );
//            throw $e;
//        }        
//     }
//    
//    
//    
//    
//    
//    
//    
//    
//    public function testGet() {               

//        
//        1;
//    }
//    
//    
//    
//    
//    
//    
//    public function testRemove() {                   
//        $entity2 = $this->rabbitRepository->get($this->rabbitIdentity1);
//        $this->assertContainsOnlyInstancesOf( RabbitEntity::class, [$entity2] );              
//        $this->assertInstanceOf(RabbitEntity::class, $entity2);  
//           
//        $this->rabbitRepository->remove($entity2);    
//        $this->rabbitRepository->flush();        
//        
//        $entity3 = $this->rabbitRepository->get($this->rabbitIdentity1);
//        $this->assertNull($entity3);   
//    }
//    
//    
//           
//     
//     
//    public function test_NelzeMazatPravePridanouEntitu() {          
//        $this->rabbitRepository->add($this->rabbitEntity1);  //entity1 do ->new[]
//        
//        $this->expectException( OperationWithLockedEntityException::class );
//        $this->rabbitRepository->remove($this->rabbitEntity1);
//    }
//    
//    
//    
//    public function test_NelzeMazatPraveSmazanouEntitu() {  
//        $this->rabbitRepository->add($this->rabbitEntity1);
//        $this->rabbitRepository->flush();
//
//        $this->rabbitRepository->remove($this->rabbitEntity1);   // entity1 do ->removed[]
//        
//        $this->expectException( OperationWithLockedEntityException::class ); 
//        $this->rabbitRepository->remove($this->rabbitEntity1);                
//    }
//    
//    //------------------------------------------------------------------------------
//    public function test_NelzeZapisovatDoReadOnlyRepository() {          
//       // $this->expectException( UnableWriteToReadOnlyRepoException::class );               
//        try {            
//            $this->rabbitRepositoryReadOnly->add($this->rabbitEntity1);
//        }
//        catch ( \Exception $e) {
//            $this->expectException( UnableWriteToReadOnlyRepoException::class );
//            throw $e;
//        }                           
//    }
//    
//    public function test_NelzeMazatZReadOnlyRepository() {          
//       // $this->expectException( UnableWriteToReadOnlyRepoException::class );               
//        try {            
//            $this->rabbitRepositoryReadOnly->remove($this->rabbitEntity1);
//        }
//        catch ( \Exception $e) {
//            $this->expectException( UnableWriteToReadOnlyRepoException::class );
//            throw $e;
//        }                           
//    }    
//    
    
    
    
    protected function tearDown(): void
    {              
            $this->rabbitRepository->__destruct();
    }
    
    
}
