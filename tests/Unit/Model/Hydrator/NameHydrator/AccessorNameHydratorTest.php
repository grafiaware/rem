<?php
namespace Test\E_AcceessorNameIdentityHydratorTest;

use PHPUnit\Framework\TestCase;

use Model\Hydrator\NameHydrator\AccessorNameHydrator;
use Model\Hydrator\NameHydrator\AccessorNameHydratorInterface;


/**
 * Description of AttributeNameHydratorTest
 *
 * @author vlse2610
 */
class AccessorNameEntityHydratorTest extends TestCase {
    /**
     *
     * @var AccessorNameHydratorInterface
     */
    private $attributeNameEntityHydrator;
    
    
    public function setUp(): void {   
        $this->attributeNameEntityHydrator = new AccessorNameHydrator( );  
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

