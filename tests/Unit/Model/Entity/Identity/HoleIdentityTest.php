<?php

namespace Test\HoleIdentityTest;

use PHPUnit\Framework\TestCase;

use Model\Testovaci\Identity\HoleIdentity;
use Model\Testovaci\Identity\HoleIdentityInterface;


/**
 * Description of RabbitIdentityTest
 *
 * @author vlse2610
 */
class HoleIdentityTest  extends TestCase  {        
    
    private $id;
    
    
    public function setUp(): void {    
        $this->id = 'identity';
    }
    
    
    
    public function testConstruct(  ) : void {                          
        $this->assertInstanceOf( HoleIdentity::class, new HoleIdentity ());                
    }
    
    
    public function test_setGetId1(  ) : void {
       $holeIdentity = new HoleIdentity();       
       $holeIdentity->setId($this->id);
       
       $this->assertEquals($this->id, $holeIdentity->getId() ) ;              
    }
     
    
    public function test_getTypeIdentity() : void {
        $holeIdentity = new HoleIdentity(); 
        $holeIdentityInterfaceName = $holeIdentity->getTypeIdentity();
        
        $this->assertEquals( HoleIdentityInterface::class ,$holeIdentityInterfaceName);
    }
   
    
 
}
