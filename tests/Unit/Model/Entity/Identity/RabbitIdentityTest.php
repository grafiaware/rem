<?php
namespace Test\RabbitIdentityTest;

use PHPUnit\Framework\TestCase;

use Model\Testovaci\Identity\RabbitIdentity;
use Model\Testovaci\Identity\RabbitIdentityInterface;


/**
 * Description of RabbitIdentityTest
 *
 * @author vlse2610
 */
class RabbitIdentityTest  extends TestCase  {        
    private $id;
    
    public function setUp(): void {    
        $this->id = 'identity';
    }
    
    
    
    public function testConstruct(  ) : void {                          
        $this->assertInstanceOf( RabbitIdentity::class, new RabbitIdentity ());                
    }
    
    
    public function test_setGetId1(  ) : void {
       $rabbitIdentity = new RabbitIdentity();       
       $rabbitIdentity->setId1($this->id);
       
       $this->assertEquals($this->id, $rabbitIdentity->getId1() ) ;              
    }
     
    
    public function test_getTypeIdentity() : void {
        $rabbitIdentity = new RabbitIdentity(); 
        $rabbitIdentityInterfaceName = $rabbitIdentity->getTypeIdentity();
        
        $this->assertEquals( RabbitIdentityInterface::class ,$rabbitIdentityInterfaceName);
    }
   
    
 
}