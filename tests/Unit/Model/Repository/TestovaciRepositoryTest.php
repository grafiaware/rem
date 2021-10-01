<?php
namespace Test\TestovaciRepositoryTest;

use PHPUnit\Framework\TestCase;

use Model\Testovaci\Repository\TestovaciRepository;
use Model\Testovaci\RowObjectManager\TestovaciRowObjectManager;

use Model\Hydrator\OneToOneAccessorHydrator;
use Model\Hydrator\NameHydrator\AccessorMethodNameHydrator;
use Model\Hydrator\Filter\OneToOneFilter;

use Model\Testovaci\Entity\TestovaciEntity;
use Model\Testovaci\Identity\TestovaciIdentity;

        


/**
 * Description of Testovaci RepositoryTest
 *
 * @author vlse2610
 */
class TestovaciRepositoryTest  extends TestCase{
//######################################### TEST TEST TEST ####################################
    

    /**
     * pro prenos objektu mezi prikazy 
     * @var TestovaciRowObjectManager 
     */
    private $rowObjectManagerROM;
    
            
    
    public function setUp(): void {
        
    }
   
    
    
    
    
    public function testAdd() { 
        $testovaciRowObjectManager  = new TestovaciRowObjectManager();    
        
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
        
        $testovaciRepository = new TestovaciRepository( $accessorHydratorEntity, $accessorHydratorIdentity, $testovaciRowObjectManager);
              
        $identity = new TestovaciIdentity(); 
            $identity->setId1('66');         
            $identity->setId2('33') ;
        $entity1 = new TestovaciEntity( $identity );              
            $entity1->setCeleJmeno("Jméno Celé"); 
            $entity1->setPrvekVarchar('') ;
            $entity1->setPrvekDatetime(new \DateTime('2000-01-01')) ;
                     
        $testovaciRepository->add($entity1);      //######## add, do new #######       
        // entity1 (je v new), (tj.) neni v collection,
         
        //######## get, 
        $entity2 = $testovaciRepository->get($identity);    //recreateEntity nevytvori NOVY object $entity2, tj. dobre
        // entity1 je stale v new  a je locked
        $this->assertNull($entity2);
        
        
        
        $testovaciRepository->flush();  
        // entity1 se   "pomoci rOManager->add  posune smerem k  ulozisti" = rowObject se prida do rOManagera
       
        //  // entity1 uz neni locked   //?????
        
        
        $entity2 = $testovaciRepository->get($identity);    
        //recreateEntity vytvori NOVY object $entity2 , //entity2 je v collection  
        $this->assertIsObject($entity2);
       
        $this->assertContainsOnlyInstancesOf( TestovaciEntity::class, [$entity2] );              
        $this->assertInstanceOf(TestovaciEntity::class, $entity2);        
               
        //$this->assertEquals($entity1, $entity2 ); /*nevhodne na objekty*/  //$this->assertObjectEquals( $entity1 , $entity2); /* takovy assert neexistuje */     
        
        $e1Hodnota = $entity1->getCeleJmeno();              
        $e2Hodnota = $entity2->getCeleJmeno();
        $this->assertEquals($e1Hodnota, $e2Hodnota);

        $e1Hodnota = $entity1->getPrvekDatetime();              
        $e2Hodnota = $entity2->getPrvekDatetime();
        //$this->assertEquals($e1Hodnota, $e2Hodnota);

        $e1Hodnota = $entity1->getPrvekVarchar();              
        $e2Hodnota = $entity2->getPrvekVarchar();
        $this->assertEquals($e1Hodnota, $e2Hodnota);

        $entity1->setCeleJmeno("Cecilka Nová");   //zmenim entity1   
        $this->assertEquals("Cecilka Nová", $entity1->getCeleJmeno() );
        //entity2 je jiny objekt nez entity1
        $this->assertNotEquals("Cecilka Nová", $entity2->getCeleJmeno() ); //je to jiny objekt
        
        //CO JE V NEW - nic
        //CO JE V COLLECTION - entity2 , s indexem
        
        

        $testovaciRepository->flush();  
            //pro new --- ROManager->add(rowObject),  new=[]
            //pro collection  --- ROManager->flush,   collection=[]
        
        $this->assertEquals([], $testovaciRepository->getCollectionProTest() );
        $this->assertEquals([], $testovaciRepository->getNewProTest() );
        $this->assertEquals([], $testovaciRepository->getRemovedProTest() );
        
    }   
    
    
     
    
    
    /**
     * 
     */
    public function testRemove() { 
        //-------test remove z uloziste  s flush ---------
        $testovaciRowObjectManager  = new TestovaciRowObjectManager();       
       
        $methodNameHydrator = new AccessorMethodNameHydrator();        
        $poleJmenIdentity = [ 
            "id1", "id2",];
        $poleJmenEntity = $poleJmenAttributes =  [             
            "prvekVarchar", "prvekDatetime", "celeJmeno"];
//            "prvekChar" , "prvekVarchar", "prvekInteger" ,"prvekText", "prvekBoolean",  
//            "prvekDate", "prvekDatetime", "prvekTimestamp" , 'jmenoClovek', 'prijmeniClovek' 
//            ] ; 
        $filterEntity = new OneToOneFilter($poleJmenEntity);
        $filterIdentity = new OneToOneFilter($poleJmenIdentity);
        $accessorHydratorEntity = new OneToOneAccessorHydrator($methodNameHydrator, $filterEntity) ;
        $accessorHydratorIdentity = new OneToOneAccessorHydrator($methodNameHydrator, $filterIdentity) ;
            
        $testovaciRepository1 = new TestovaciRepository( $accessorHydratorEntity, $accessorHydratorIdentity, $testovaciRowObjectManager );
              
        $identity = new TestovaciIdentity();
            $identity->setId1('66');         
            $identity->setId2('33') ;
        $entity = new TestovaciEntity($identity);
            $entity->setCeleJmeno("Jméno Celé"); 
            $entity->setPrvekVarchar('') ;
            $entity->setPrvekDatetime(new \DateTime('2000-01-01')) ;
       
        $testovaciRepository1->add($entity);     //entity v new
        $testovaciRepository1->flush();             
        //****dostala se az do uloziste****, neni , ani new, ani remove. ............ zatim je v rowObject 
       
        $this->rowObjectManagerROM =  clone $testovaciRowObjectManager;
        
        $testovaciRepository1->remove($entity);  //  #### remove #####  //entity v remove       
        $testovaciRepository1->flush();          //entity neni v repository ani v new, ani v remove, ani v collection
                                                 
        //****vymazani se dostalo se az do uloziste*** - entita v repository proste neexistuje
        
        $entity21 = $testovaciRepository1->get($identity); // vraci null        
        // test null
        $this->assertIsNotObject($entity21);
        $this->assertNull($entity21);
        
        
        
        
        
        //---------- pro dokresleni co se deje kdyz
        
        //jine nove repository se starym rowObjectManagerROM
        $testovaciRepository2 = new TestovaciRepository($accessorHydratorEntity, $accessorHydratorIdentity, $this->rowObjectManagerROM );   
        $entity21 = $testovaciRepository2->get($identity); // vraci drive ulozeny objekt
        $this->assertIsObject($entity21);
        
        $this->assertInstanceOf(TestovaciEntity::class, $entity21);
        
   
                
        
       
   
//        //-------------------------------------------------------------------------------------
//        //-------test remove  bez flush , tj z pole remove   ??  odkud vlastne ---------
//        $this->rowObjectManagerROM  = new TestovaciRowObjectManager();       
//       
//        $methodNameHydrator = new AccessorMethodNameHydrator();
//        $poleJmen = $poleJmenAttributes =  [ 
////            "id1", "id2"
////            "prvekVarchar", "prvekDatetime", "celeJmeno"
////            "prvekChar" , "prvekVarchar", "prvekInteger" ,"prvekText", "prvekBoolean",  
////            "prvekDate", "prvekDatetime", "prvekTimestamp" , 'jmenoClovek', 'prijmeniClovek' 
//            ] ; 
//        $filter = new OneToOneFilter($poleJmen);
//        $accessorHydrator = new OneToOneAccessorHydrator($methodNameHydrator, $filter) ;
//        $testovaciRepository1 = new TestovaciRepository($accessorHydrator, $this->rowObjectManagerROM );
//              
//        $identity = new TestovacIdentity();
//        $entity1 = new TestovaciEntity($identity);
//       
//        $testovaciRepository1->add($entity1);       
//        //zustal v new -- asi
//        $testovaciRepository1->remove($entity1);         //TADY HLASI VYJIMKU-
//        
//        $entity2 = $testovaciRepository1->get($identity);  //nema vratit nic Xnebo  chybu?
//        //assert
//        
    }    
        
  
    /**
     * get - ma vracet persistovanou z repository ale i z uloziste(db)
     */
    public function testGet() {
        //-------test get z uloziste  ---------         
        $this->rowObjectManagerROM  = new TestovaciRowObjectManager();       
       
        $methodNameHydrator = new AccessorMethodNameHydrator();        
        $poleJmenIdentity = [ 
            "id1", "id2",];
        $poleJmenEntity = $poleJmenAttributes =  [             
            "prvekVarchar", "prvekDatetime", "celeJmeno"];
//            "prvekChar" , "prvekVarchar", "prvekInteger" ,"prvekText", "prvekBoolean",  
//            "prvekDate", "prvekDatetime", "prvekTimestamp" , 'jmenoClovek', 'prijmeniClovek' 
//            ] ; 
        $filterEntity = new OneToOneFilter($poleJmenEntity);
        $filterIdentity = new OneToOneFilter($poleJmenIdentity);
        $accessorHydratorEntity = new OneToOneAccessorHydrator($methodNameHydrator, $filterEntity) ;
        $accessorHydratorIdentity = new OneToOneAccessorHydrator($methodNameHydrator, $filterIdentity) ;
        
        $testovaciRepository1 = new TestovaciRepository($accessorHydratorEntity, $accessorHydratorIdentity, $this->rowObjectManagerROM );
              
        $identity = new TestovaciIdentity();
        $entity1 = new TestovaciEntity($identity);
       
        $testovaciRepository1->add($entity1);     
        $testovaciRepository1->flush();   //donuti "zapsat do uloziste" - nebo zatim alespon "nekam do ROManagera" , napr. do pole
       
        //nove=jine repository se stejnym ROManagerem
        $testovaciRepository2 = new TestovaciRepository($accessorHydratorEntity,$accessorHydratorIdentity, $this->rowObjectManagerROM );   
        $entity2 = $testovaciRepository2->get($identity);
       
        //$this->assertEquals($entity1, $entity2 );
       
        
       
        //------- test get jen z pole collection? , nebo jen z pole new? , ...   -----  jen z remote  - tady by asi nemel vratit nic
        //  ..
    }   //  ..
    
        
      
    
    
}
