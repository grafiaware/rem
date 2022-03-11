<?php
namespace Test\IndexMakerTest;

use PHPUnit\Framework\TestCase;

use Model\Testovaci\Identity\RabbitIdentity;

use Model\Hydrator\NameHydrator\AccessorMethodNameHydrator;
use Model\Hydrator\NameHydrator\AccessorMethodNameHydratorInterface;
use Model\Hydrator\OneToOneAccessorHydrator;

use Model\Filter\OneToOneFilter;
use Model\Filter\OneToOneFilterInterface;

use Model\IdentityMap\IndexMaker\IndexMaker;



/**
 * Description of IndexMakerTest
 *
 * @author vlse2610
 */
class IndexMakerTest  extends TestCase{
      
    
    
    protected function setUp(): void {
                              
        //--------------------------------------------------
    }
    
    
    
    
    public function test_indexFromIdentity() {    
        $methodNameHydrator = new AccessorMethodNameHydrator();     
        $indexMaker = new IndexMaker( $methodNameHydrator );
                
        $rabbitIdentity = new RabbitIdentity(); 
        $rabbitIdentity->setId1( "01" );
        $rabbitIdentity->setId2( "02" );
        $poleJmenIdentityRabbit =   [ "id1", "id2" ];                
        $filterRabbitIdentity = new OneToOneFilter( $poleJmenIdentityRabbit);        
        //$filtersI [] = $filterIdentity;
        $vyrobenyIndex = $indexMaker->indexFromIdentity( $rabbitIdentity, $filterRabbitIdentity);
         
        $this->assertIsString($vyrobenyIndex);
        $this->assertEquals( '0102', $vyrobenyIndex);
        
        

    
    }    
    
}    