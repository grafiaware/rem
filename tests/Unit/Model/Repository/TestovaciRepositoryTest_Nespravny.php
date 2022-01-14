<?php
namespace Test\TestovaciRepositoryTest;

use PHPUnit\Framework\TestCase;

use Model\Testovaci\Repository\TestovaciRepository;
use Model\Testovaci\RowObjectManager\TestovaciRowObjectManager;

use Model\Hydrator\OneToOneAccessorHydrator;
use Model\Hydrator\NameHydrator\AccessorMethodNameHydrator;
use Model\Filter\OneToOneFilter;

use Model\Testovaci\Entity\TestovaciEntity;
use Model\Testovaci\Identity\RabbitIdentity;

        


/**
 * Description of Testovaci RepositoryTest
 *
 * @author vlse2610
 */
class TestovaciRepositoryTest_Nespravny  extends TestCase{
//######################################### TEST TEST TEST ####################################
    

    /**
     * pro prenos objektu mezi prikazy 
     * @var TestovaciRowObjectManager 
     */
    //private $rowObjectManagerROM;
    
            
    
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
        
        $testovaciRepository1 = new TestovaciRepository( $accessorHydratorEntity, $accessorHydratorIdentity, $testovaciRowObjectManager);
              
        $identity = new RabbitIdentity(); 
            $identity->setId1('66');         
            $identity->setId2('33') ;
        $entity1 = new TestovaciEntity( $identity );              
            $entity1->setCeleJmeno("Jméno Celé"); 
            $entity1->setPrvekVarchar('') ;
            $entity1->setPrvekDatetime(new \DateTime('2000-01-01')) ;
        //##########    ##########    ########     #########     #########   #######    
                     
        $testovaciRepository1->add($entity1);      //######## add, do new #######       
        // entity1 (je v new), (tj.) neni v collection,
        $this->assertFalse( $entity1->isPersisted() ); 
        
        //######## get 
        $entity2 = $testovaciRepository1->get($identity);    //recreateEntity nevytvori NOVY object $entity2, tj. dobre
        // entity1 je stale v new  a je locked
        $this->assertTrue( $entity1->isLocked() ); 

        $this->assertNull($entity2);
        //-----------------------------------------------------------
        
                
        //######## flush
        $testovaciRepository1->flush();  
        // "entity1 se  "pomoci rOManager->add  posune smerem k  ulozisti" = rowObject se prida do rOManagera       
        // entity1 neni locked        
        $this->assertTrue( $entity1->isPersisted() );
        $this->assertFalse( $entity1->isLocked() ); 

        
        $entity2 = $testovaciRepository1->get($identity);    
        //recreateEntity vytvori NOVY object $entity2 --  je v collection  
        $this->assertIsObject($entity2);
        $this->assertTrue( $entity2->isPersisted() ); 
        $this->assertFalse( $entity1->isLocked() ); 
       
        $this->assertContainsOnlyInstancesOf( TestovaciEntity::class, [$entity2] );              
        $this->assertInstanceOf(TestovaciEntity::class, $entity2);                       
        //$this->assertEquals($entity1, $entity2 ); /*nevhodne na objekty*/  //$this->assertObjectEquals( $entity1 , $entity2); /* takovy assert neexistuje */     
        
        $e1Hodnota = $entity1->getCeleJmeno();              
        $e2Hodnota = $entity2->getCeleJmeno();
        $this->assertEquals($e1Hodnota, $e2Hodnota);

        $e1Hodnota = $entity1->getPrvekDatetime();              
        $e2Hodnota = $entity2->getPrvekDatetime(); 
        $this->assertEquals( $e1Hodnota->format( "d-m-Y H:i:s"),   $e2Hodnota->format( "d-m-Y H:i:s" ) );

        $e1Hodnota = $entity1->getPrvekVarchar();              
        $e2Hodnota = $entity2->getPrvekVarchar();
        $this->assertEquals($e1Hodnota, $e2Hodnota);

        $entity1->setCeleJmeno("Cecilka Nová");   //zmenim entity1   
        $this->assertEquals("Cecilka Nová", $entity1->getCeleJmeno() );
        //entity2 je jiny objekt nez entity1
        $this->assertNotEquals("Cecilka Nová", $entity2->getCeleJmeno() ); //je to jiny objekt
        
        //CO JE V NEW - nic
        //CO JE V COLLECTION - entity2 , s indexem       
        $testovaciRepository1->flush();  
            //pro new --- ROManager->add(rowObject),  new=[]
            //pro collection  ---   collection=[]
        
        $this->assertEquals([], $testovaciRepository1->getCollectionProTest() );
        $this->assertEquals([], $testovaciRepository1->getNewProTest() );
        $this->assertEquals([], $testovaciRepository1->getRemovedProTest() );
        
    }   
    
    
     
    
    
    /**
     *  test remove z uloziste 
     */
    public function testRemove() { 
        
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
        
        $testovaciRepository1 = new TestovaciRepository( $accessorHydratorEntity, $accessorHydratorIdentity, $testovaciRowObjectManager);
              
        $identity = new RabbitIdentity(); 
            $identity->setId1('66');         
            $identity->setId2('33') ;
        $entity1 = new TestovaciEntity( $identity );              
            $entity1->setCeleJmeno("Jméno Celé"); 
            $entity1->setPrvekVarchar('') ;
            $entity1->setPrvekDatetime(new \DateTime('2000-01-01')) ;
        //##########    ##########    ########     #########     #########   ####### 
            
            
            
            
        $testovaciRepository1->add($entity1);     //entity v new
        $testovaciRepository1->flush();             
        //****dostala se az do uloziste****, neni ani new, ani remove, "je" v ulozisti zde rOManager
        //----uschova rOManagera
            $rowObjectManagerROM_uschov =  clone $testovaciRowObjectManager;    
            
        $testovaciRepository1->remove($entity1);  //  #### remove #####  //entity v poli remove       
        $testovaciRepository1->flush();         
        //entity neni v repository ani v new, ani v remove, ani v collection                                                 
        //****vymazani se dostalo se az do uloziste*** - entita v repository proste neexistuje
        
        $entity2 = $testovaciRepository1->get($identity); // ma vracet a vraci null        
        // test null
        $this->assertIsNotObject($entity2);
        $this->assertNull($entity2);
                
        //----------        
        //jine nove repository se starym rowObjectManagerROM  "entita1 je v ulozisti"
        $testovaciRepository2 = new TestovaciRepository($accessorHydratorEntity, $accessorHydratorIdentity, $rowObjectManagerROM_uschov );   
        $entity21 = $testovaciRepository2->get($identity); // vraci drive ulozeny objekt        
        $this->assertIsObject($entity21);        
        $this->assertInstanceOf(TestovaciEntity::class, $entity21);
        $this->assertEquals("Jméno Celé", $entity21->getCeleJmeno()); 
                            
    }    
        
  
    
    
    /**
     * 
     * get - ma vracet persistovanou z repository ale i z uloziste(db)
     */
    public function testGet() {
        //-------test get z uloziste  ---------         
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
        
        $testovaciRepository1 = new TestovaciRepository( $accessorHydratorEntity, $accessorHydratorIdentity, $testovaciRowObjectManager);
              
        $identity = new RabbitIdentity(); 
            $identity->setId1('66');         
            $identity->setId2('33') ;
        $entity1 = new TestovaciEntity( $identity );              
            $entity1->setCeleJmeno("Jméno Celé"); 
            $entity1->setPrvekVarchar('') ;
            $entity1->setPrvekDatetime(new \DateTime('2000-01-01')) ;
        //##########    ##########    ########     #########     #########   ####### 
       
        $testovaciRepository1->add($entity1);     //entity v new     
        $this->assertFalse( $entity1->isPersisted() );  //jeste neni persisted

        $testovaciRepository1->flush();   //donuti "zapsat do uloziste" - nebo zatim alespon "nekam do ROManagera" , napr. do pole
        $this->assertTrue( $entity1->isPersisted() );

        
            //$this->rowObjectManagerROM =  clone $testovaciRowObjectManager;  // uschova
        
        //nove = jine repository se stejnym ROManagerem
        $testovaciRepository2 = new TestovaciRepository($accessorHydratorEntity,$accessorHydratorIdentity,$testovaciRowObjectManager );   
        $identity2 = new RabbitIdentity();
            $identity2->setId1('66');         
            $identity2->setId2('33') ;
        $entity2 = $testovaciRepository2->get($identity2);
                 
        $this->assertTrue( $entity2->isPersisted() );
        $entity2->setCeleJmeno("AAAAA BBBB");
        $this->assertNotEquals($entity1->getCeleJmeno(), $entity2->getCeleJmeno() );
       
       
       
    }   
    
        
      
    
    
}
