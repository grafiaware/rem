<?php
namespace Test\TestovaciRepositoryTest;

use PHPUnit\Framework\TestCase;

use Model\VS\Repository\TestovaciRepository;
use Model\VS\RowObjectManager\TestovaciRowObjectManager;

use Model\Hydrator\OneToOneAccessorHydrator;
use Model\Hydrator\NameHydrator\AccessorMethodNameHydrator;
use Model\Hydrator\Filter\OneToOneFilter;

use Model\VS\Entity\TestovaciEntity;
use Model\VS\Identity\TestovacIdentity;

        


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
//            "prvekChar" , "prvekVarchar", "prvekInteger" ,"prvekText", "prvekBoolean",  
//            "prvekDate", "prvekDatetime", "prvekTimestamp" , 'jmenoClovek', 'prijmeniClovek' 
            ] ; 
       $filter = new OneToOneFilter($poleJmen);
       $accessorHydrator = new OneToOneAccessorHydrator($methodNameHydrator, $filter) ;
       $testovaciRepository = new TestovaciRepository($accessorHydrator, $testovaciRowObjectManager);
              
       $identity = new TestovacIdentity();
       $entity1 = new TestovaciEntity($identity);
       
       $testovaciRepository->add($entity1);       
       $entity2 = $testovaciRepository->get($identity);
       
       //$this->assertEquals($entity1, $entity2 );
       $this->assertIsObject($entity2);
       $this->assertInstanceOf(TestovaciEntity::class, $entity2);
             
    }   
    
    
    
    /**
     * get - ma vracet persistovanou z repository ale i z uloziste(db)
     */
    public function testGet() {
        //-------test get z uloziste  ---------         
        $this->rowObjectManagerROM  = new TestovaciRowObjectManager();       
       
        $methodNameHydrator = new AccessorMethodNameHydrator();
        $poleJmen = $poleJmenAttributes =  [ 
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
       
        $this->assertEquals($entity1, $entity2 );
       
        
       
        //------- test get jen z collection? , nebo jen z new? , ...   -----  jen z remote  - tady by asi nemel vratit nic
        //  ..
    }   //  ..
    
    
    
    
    /**
     * 
     */
    public function testRemove() { 
        //-------test remove z uloziste  s flush ---------
        $this->rowObjectManagerROM  = new TestovaciRowObjectManager();       
       
        $methodNameHydrator = new AccessorMethodNameHydrator();
        $poleJmen = $poleJmenAttributes =  [ 
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
        
        $testovaciRepository1->remove($entity1);  //TADY HLASI VYJIMKU--asi to tkhle nechceme
        $testovaciRepository1->flush(); 
        
        $testovaciRepository2 = new TestovaciRepository($accessorHydrator, $this->rowObjectManagerROM );   
        $entity2 = $testovaciRepository->get($identity); //nema vratit nic
        //assert
        
        
        
        
        //-------test remove z uloziste bez flush , tj z remove   ??  odkud vlastne ---------
        $this->rowObjectManagerROM  = new TestovaciRowObjectManager();       
       
        $methodNameHydrator = new AccessorMethodNameHydrator();
        $poleJmen = $poleJmenAttributes =  [ 
//            "prvekChar" , "prvekVarchar", "prvekInteger" ,"prvekText", "prvekBoolean",  
//            "prvekDate", "prvekDatetime", "prvekTimestamp" , 'jmenoClovek', 'prijmeniClovek' 
            ] ; 
        $filter = new OneToOneFilter($poleJmen);
        $accessorHydrator = new OneToOneAccessorHydrator($methodNameHydrator, $filter) ;
        $testovaciRepository1 = new TestovaciRepository($accessorHydrator, $this->rowObjectManagerROM );
              
        $identity = new TestovacIdentity();
        $entity1 = new TestovaciEntity($identity);
       
        $testovaciRepository1->add($entity1);             
        $testovaciRepository1->remove($entity1);         //TADY HLASI VYJIMKU-
        
        $entity2 = $testovaciRepository1->get($identity);  //nema vratit nic Xnebo  chybu?
        //assert
        
        
        
        
        //-------test remove z repository s flush -repository puvodni,  odkud vlastne ---------
        $this->rowObjectManagerROM  = new TestovaciRowObjectManager();       
       
        $methodNameHydrator = new AccessorMethodNameHydrator();
        $poleJmen = $poleJmenAttributes =  [ 
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
        
        $testovaciRepository1->remove($entity1); //ma vratit asi  chybu?
                
        //assert tet
        
        
        
        
        
    }   
    
    
}
