<?php
namespace Test\TestovaciRepositoryTest;

use PHPUnit\Framework\TestCase;
use Model\Testovaci\RowObjectManager\TestovaciRowObjectManager;
use Model\Testovaci\Repository\TestovaciRepository;


/**
 * Description of TestovaciRepositoryTestA
 *
 * @author vlse2610
 */
class TestovaciRepositoryTestA {
    /**
     * 
     */
    private $rowObjectManager ;
    
    private $testovaciRepository;
    
    
    
    
    public function setUpBeforeClass(): void {
         $this->rowObjectManager = new TestovaciRowObjectManager();
        
    }
    
    
    public function setUp(): void {
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

        $this->testovaciRepository = new TestovaciRepository( $accessorHydratorEntity, $accessorHydratorIdentity,  $this->rowObjectManager);
              
    }
    
    
    
    
    public function testAdd_1() { 
        $identity1 = new TestovaciIdentity(); 
            $identity1->setId1('66');         
            $identity1->setId2('33') ;
        $entity1 = new TestovaciEntity( $identity1 );              
            $entity1->setCeleJmeno("Jméno Celé"); 
            $entity1->setPrvekVarchar('') ;
            $entity1->setPrvekDatetime(new \DateTime('2000-01-01')) ;            
            
        $this->testovaciRepository->add($entity1);
        $this->testovaciRepository->flush();
        
            
    
    }
    
    public function testAdd_2() { 
        $identity1 = new TestovaciIdentity(); 
            $identity1->setId1('66');         
            $identity1->setId2('33') ;
        $entity1 = new TestovaciEntity( $identity1 );              
            $entity1->setCeleJmeno("Jméno Celé"); 
            $entity1->setPrvekVarchar('') ;
            $entity1->setPrvekDatetime(new \DateTime('2000-01-01')) ;
    
        $this->testovaciRepository->get($identity1);
        
    }
