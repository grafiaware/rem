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
       $methodNameHydrator = new AccessorMethodNameHydrator();
       $poleJmen = $poleJmenAttributes =  [ 
//           "id_1", "id_2"
//            "prvekVarchar", "prvekDatetime", "celeJmeno"
//            "prvekChar" , "prvekVarchar", "prvekInteger" ,"prvekText", "prvekBoolean",  
//            "prvekDate", "prvekDatetime", "prvekTimestamp" , 'jmenoClovek', 'prijmeniClovek' 
            ] ; 
       $filter = new OneToOneFilter($poleJmen);
       $accessorHydrator = new OneToOneAccessorHydrator($methodNameHydrator, $filter) ;
       $testovaciRepository = new TestovaciRepository($accessorHydrator, $testovaciRowObjectManager);
              
       $identity = new TestovacIdentity();
       
       $entity1 = new TestovaciEntity($identity);       
       $testovaciRepository->add($entity1);       
       
       $entity2 = $testovaciRepository->get($identity);/**/
       
       //$this->assertEquals($entity1, $entity2 );
       $this->assertIsObject($entity2);
       //$this->assertInstanceOf(TestovaciEntity::class, $entity2);
             
    }   
    
    
     
    
    
    /**
     * 
     */
    public function testRemove() { 
        //-------test remove z uloziste  s flush ---------
        $this->rowObjectManagerROM  = new TestovaciRowObjectManager();       
       
        $methodNameHydrator = new AccessorMethodNameHydrator();
        $poleJmen = $poleJmenAttributes =  [ 
//           "id_1", "id_2"
//            "prvekVarchar", "prvekDatetime", "celeJmeno"
//            "prvekChar" , "prvekVarchar", "prvekInteger" ,"prvekText", "prvekBoolean",  
//            "prvekDate", "prvekDatetime", "prvekTimestamp" , 'jmenoClovek', 'prijmeniClovek' 
            ] ; 
        $filter = new OneToOneFilter($poleJmen);
        $accessorHydrator = new OneToOneAccessorHydrator($methodNameHydrator, $filter) ;
        $testovaciRepository1 = new TestovaciRepository($accessorHydrator, $this->rowObjectManagerROM );
              
        $identity = new TestovacIdentity();
        $entity1 = new TestovaciEntity($identity);
       
        $testovaciRepository1->add($entity1);     
        $testovaciRepository1->flush(); 
        //****dostala se az do uloziste****, neni v collection, ani new, ani remove
        
        $testovaciRepository1->remove($entity1);  //TADY HLASLO VYJIMKU--asi to tkhle nechceme
        //OperationWithLockedEntityException
        
        $testovaciRepository1->flush(); 
        //****dostalo se az do uloziste*** - entita proste neexistuje
        
        $testovaciRepository2 = new TestovaciRepository($accessorHydrator, $this->rowObjectManagerROM );   
        $entity2 = $testovaciRepository2->get($identity); //nema vratit nic ???
        
        //nema vratit nic ??? --nevim momentalne
        
        $this->assertIsObject($entity2);
        //$this->assertIsNotObject($entity2);
        
        
        
       
//        
//        //-------------------------------------------------------------------------------------
//        //-------test remove  bez flush , tj z pole remove   ??  odkud vlastne ---------
//        $this->rowObjectManagerROM  = new TestovaciRowObjectManager();       
//       
//        $methodNameHydrator = new AccessorMethodNameHydrator();
//        $poleJmen = $poleJmenAttributes =  [ 
////            "id_1", "id_2"
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
        $poleJmen = $poleJmenAttributes =  [ 
//           "id_1", "id_2"
//            "prvekVarchar", "prvekDatetime", "celeJmeno"            
//            "prvekChar" , "prvekVarchar", "prvekInteger" ,"prvekText", "prvekBoolean",  
//            "prvekDate", "prvekDatetime", "prvekTimestamp" , 'jmenoClovek', 'prijmeniClovek' 
            ] ; 
        $filter = new OneToOneFilter($poleJmen);
        $accessorHydrator = new OneToOneAccessorHydrator($methodNameHydrator, $filter) ;
        $testovaciRepository1 = new TestovaciRepository($accessorHydrator, $this->rowObjectManagerROM );
              
        $identity = new TestovacIdentity();
        $entity1 = new TestovaciEntity($identity);
       
        $testovaciRepository1->add($entity1);     
        $testovaciRepository1->flush();   //donuti "zapsat do uloziste" - nebo zatim alespon "nekam do ROManagera" , napr. do pole
       
        //nove=jine repository se stejnym ROManagerem
        $testovaciRepository2 = new TestovaciRepository($accessorHydrator, $this->rowObjectManagerROM );   
        $entity2 = $testovaciRepository2->get($identity);
       
        //$this->assertEquals($entity1, $entity2 );
       
        
       
        //------- test get jen z pole collection? , nebo jen z pole new? , ...   -----  jen z remote  - tady by asi nemel vratit nic
        //  ..
    }   //  ..
    
        
      
    
    
}
