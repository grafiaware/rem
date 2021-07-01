<?php
//namespace Test\I_AttributeNameIdentityHydratorTest;

use PHPUnit\Framework\TestCase;

use Model\Hydrator\NameHydrator\AttributeNameHydrator;

/**
 * Description of AttributeNameIdentityHydratorTest
 *
 * @author vlse2610
 */
class AttributeNameIdentityHydratorTest__ extends TestCase {
    
    public function setUp(): void {                
    }
    
    
    
    public function test() : void {                  
        $identityNameHydrator = new AttributeNameHydrator( );  
        
        $this->assertIsObject($identityNameHydrator);
   
    }
    
}
