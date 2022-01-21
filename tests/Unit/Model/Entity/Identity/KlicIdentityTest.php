<?php
namespace Test\KlicIdentityTest;

use PHPUnit\Framework\TestCase;

use Model\Testovaci\Identity\KlicIdentity;
use Model\Testovaci\Identity\KlicIdentityInterface;


/**
 * Description of KlicIdentityTest
 *
 * @author vlse2610
 */
class KlicIdentityTest  extends TestCase{
    
    private $id;
    
    
    public function setUp(): void {    
        $this->id = 'identity';
    }
    
    
    
    public function testConstruct(  ) : void {                          
        $this->assertInstanceOf( KlicIdentity::class, new KlicIdentity ());                
    }
    
    
    public function test_setGetKlic(  ) : void {
       $klicIdentity = new KlicIdentity();       
       $klicIdentity->setKlic($this->id);
       
       $this->assertEquals($this->id, $klicIdentity->getKlic() ) ;              
    }
     
    
    public function test_getTypeIdentity() : void {
        $klicIdentity = new KlicIdentity();       
        $klicIdentityInterfaceName = $klicIdentity->getTypeIdentity();
        
        $this->assertEquals( KlicIdentityInterface::class ,$klicIdentityInterfaceName);
    }
   
    
 
}
