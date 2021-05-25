<?php
namespace Test\E_AttributeNameIdentityHydratorTest;

use PHPUnit\Framework\TestCase;

use Model\Entity\Hydrator\NameHydrator\AttributeNameHydrator;
use Model\Entity\Hydrator\NameHydrator\AttributeNameHydratorInterface;


/**
 * Description of AttributeNameHydratorTest
 *
 * @author vlse2610
 */
class AttributeNameEntityHydratorTest extends TestCase {
    /**
     *
     * @var AttributeNameHydratorInterface
     */
    private $attributeNameEntityHydrator;
    
    
    public function setUp(): void {   
        $this->attributeNameEntityHydrator = new AttributeNameHydrator( );  
    }
    
    
    
    public function test() : void {                                 
        $this->assertIsObject(  $this->attributeNameEntityHydrator  );   
    }
    
    
    
    public function testHydrate() : void {                          
        $name = "abcDEFěščř";
        $hydrName = $this->attributeNameEntityHydrator->hydrate($name);
        $this->assertEquals( $name,  $hydrName, "**CHYBA**při hydrataci");   
    }
    
    
    
    public function testExtract() : void {                  
        $name = "abcDEFěščř";
        $extrName = $this->attributeNameEntityHydrator->extract($name);
        $this->assertEquals( $name,  $extrName, "**CHYBA**při extrahovani");   
   
    }
    
    
}

