<?php

namespace Test\CarrotIdentityTest;

use PHPUnit\Framework\TestCase;

use Model\Testovaci\Identity\CarrotIdentity;
use Model\Testovaci\Identity\CarrotIdentityInterface;


/**
 * Description of RabbitIdentityTest
 *
 * @author vlse2610
 */
class CarrotIdentityTest  extends TestCase  {        
    
    private $id;
    
    
    public function setUp(): void {    
        $this->id = 'identity';
    }
    
    
    
    public function testConstruct(  ) : void {                          
        $this->assertInstanceOf( CarrotIdentity::class, new CarrotIdentity ());                
    }
    
    
    public function test_setGetId(  ) : void {
       $carrotIdentity = new CarrotIdentity();       
       $carrotIdentity->setId($this->id);
       
       $this->assertEquals( $this->id, $carrotIdentity->getId() ) ;              
    }
     
    
    public function test_getTypeIdentity() : void {
        $carrotIdentity = new CarrotIdentity(); 
        $carrotIdentityInterfaceName = $carrotIdentity->getTypeIdentity();
        
        $this->assertEquals( CarrotIdentityInterface::class, $carrotIdentityInterfaceName);
    }
}