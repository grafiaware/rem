<?php
namespace Test\CeleJmenoGluerTest;

use PHPUnit\Framework\TestCase;

use Model\Entity\Hydrator\CeleJmenoGluer;
use Model\Entity\Hydrator\CeleJmenoGluerInterface;


//###################################################################################

/**
 * 
 */
class CeleJmenoGluerTest  extends TestCase {     
    /**
     *
     * @var CeleJmenoGluerInterface 
     */
    private $gluer;
    
    
    public function setUp(): void {
        $this->gluer = new CeleJmenoGluer( );
         
    }
    
    
    public function teststick(  ) : void {   
        $listJmen =      [ 'prvniKus', 'druhyKus'];
        $castiJmenaZRO = [ 'druhyKus' => 'VONÁŠEK',  'prvniKus' => 'JOSEF' ];
        
        $celeJmeno = $this->gluer->stick($castiJmenaZRO, $listJmen);                                
        $this->assertEquals( 'JOSEF|VONÁŠEK', $celeJmeno, " **CHYBA** ") ;                
    }
    
    
    public function testunstick(  ) : void {         
        $celeJmeno = 'Růžena|Oulehlová';
        $listJmen =   [ 'jmeno', 'prijmeni' ];
        
        $castiJmena = $this->gluer->unstick($celeJmeno, $listJmen);
        
        $this->assertIsArray( $castiJmena );
        $this->assertArrayHasKey( 'jmeno' , $castiJmena,  " **CHYBA** " );  
        $this->assertArrayHasKey( 'prijmeni' , $castiJmena,  " **CHYBA** " );  
        
        $this->assertContains( 'Růžena' , $castiJmena, " **CHYBA Růženy** ");
        $this->assertContains( 'Oulehlová' , $castiJmena, " **CHYBA Oulehlové** ");        
    }
}

