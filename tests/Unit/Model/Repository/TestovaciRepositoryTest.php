<?php
namespace Test\TestovaciRepositoryTest;

use PHPUnit\Framework\TestCase;

use Model\Testovaci\Repository\TestovaciRepository;
use Model\Testovaci\RowObjectManager\TestovaciRowObjectManager;

use Model\Hydrator\OneToOneAccessorHydrator;
use Model\Hydrator\NameHydrator\AccessorMethodNameHydrator;
use Model\Hydrator\Filter\OneToOneFilter;

use Model\Testovaci\Entity\TestovaciEntity;
use Model\Testovaci\Identity\TestovacIdentity;

        


/**
 * Description of Testovaci RepositoryTest
 *
 * @author vlse2610
 */
class TestovaciRepositoryTest  extends TestCase{
//######################################### TEST TEST TEST ####################################
    
//pro prenos objektu mezi prikazy 
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
              
        $identity = new TestovacIdentity(); 
            $identity->setId1('66');         
            $identity->setId2('33') ;
        $entity1 = new TestovaciEntity( $identity );              
            $entity1->setCeleJmeno("Jméno Celé"); 
            $entity1->setPrvekVarchar('') ;
            $entity1->setPrvekDatetime(new \DateTime('2000-01-01')) ;
                     
        $testovaciRepository->add($entity1);      //######## add, do new #######
       
        
        //######## get, entity (je v new) tj.neni v collection, 
        $entity2 = $testovaciRepository->get($identity);    //recreateEntity vytvori NOVY object $entity2 #######
        $this->assertIsObject($entity2);
        
        //$this->assertEquals($entity1, $entity2 ); /*nevhodne na objekty*/  //$this->assertObjectEquals( $entity1 , $entity2); /* takovy assert neexistuje */     
        $this->assertContainsOnlyInstancesOf( TestovaciEntity::class, [$entity2] );              
        $this->assertInstanceOf(TestovaciEntity::class, $entity2);

        $e1Hodnota = $entity1->getCeleJmeno();              
        $e2Hodnota = $entity2->getCeleJmeno();
        $this->assertEquals($e1Hodnota, $e2Hodnota);

        $e1Hodnota = $entity1->getPrvekDatetime();              
        $e2Hodnota = $entity2->getPrvekDatetime();
        $this->assertEquals($e1Hodnota, $e2Hodnota);

        $e1Hodnota = $entity1->getPrvekVarchar();              
        $e2Hodnota = $entity2->getPrvekVarchar();
        $this->assertEquals($e1Hodnota, $e2Hodnota);

        $entity1->setCeleJmeno("Cecilka Nová");      
        $this->assertEquals("Cecilka Nová", $entity1->getCeleJmeno() );
        //entity2 je jiny objekt nez entity1
        $this->assertNotEquals("Cecilka Nová", $entity2->getCeleJmeno() ); //je to jiny objekt

        
        //CO JE V NEW - je entity1, bez indexu 
        //CO JE V COLLECTION - ebntity2 , s indexem
        1;
        $testovaciRepository->flush();  //CO SE STANE
        //pro new --- ROManager->add(rowObject) new=[]
        //pro collection  --- ROManager->flush   collection=[]
        
        $this->assertEquals([], $testovaciRepository->getCollectionProTest() );
        $this->assertEquals([], $testovaciRepository->getNewProTest() );
        $this->assertEquals([], $testovaciRepository->getRemovedProTest() );
        
        1;
       
    }   
    
    
     
    
    
    /**
     * 
     */
    public function testRemove() { 
        //-------test remove z uloziste  s flush ---------
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
            
        $testovaciRepository1 = new TestovaciRepository( $accessorHydratorEntity, $accessorHydratorIdentity, $this->rowObjectManagerROM );
              
        $identity = new TestovacIdentity();
            $identity->setId1('66');         
            $identity->setId2('33') ;
        $entity1 = new TestovaciEntity($identity);
            $entity1->setCeleJmeno("Jméno Celé"); 
            $entity1->setPrvekVarchar('') ;
            $entity1->setPrvekDatetime(new \DateTime('2000-01-01')) ;
       
        $testovaciRepository1->add($entity1);     //entity v new
        $testovaciRepository1->flush();             //entity !!!! ma byt v collection !!! -- PODIVAT    ############
        //****dostala se az do uloziste****, neni , ani new, ani remove
        
       
        
        $testovaciRepository1->remove($entity1);  //  #### remove #####  //entity v remove       
        $testovaciRepository1->flush();          //entity neni v repository
        //****dostalo se az do uloziste*** - entita proste neexistuje
        
        $testovaciRepository2 = new TestovaciRepository($accessorHydratorEntity, $accessorHydratorIdentity, $this->rowObjectManagerROM );   
        $entity2 = $testovaciRepository2->get($identity); // vratit null
        
      // asi, a test null
        $this->assertIsNotObject($entity2);
        $this->assertNull($entity2);
                
        
       
//        
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
              
        $identity = new TestovacIdentity();
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
