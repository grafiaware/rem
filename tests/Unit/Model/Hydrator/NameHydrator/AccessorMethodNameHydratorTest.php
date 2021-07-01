<?php
namespace Test\E_AccessorMethodNameEntityHydratorTest;

use PHPUnit\Framework\TestCase;

use Model\Hydrator\NameHydrator\AccessorMethodNameHydratorInterface;
use Model\Hydrator\NameHydrator\AccessorMethodNameHydrator;

/**
 * Description of MethodNameHydratorTest
 *
 * @author vlse2610
 */
class AccessorMethodNameEntityHydratorTest  extends TestCase {    
    /**
     *
     * @var AccessorMethodNameHydratorInterface
     */
    private $methodNameEntityHydrator;
    
    
    public function setUp(): void {   
        $this->methodNameEntityHydrator = new AccessorMethodNameHydrator( );  
    }
    
    
    
    public function test() : void {                                 
        $this->assertIsObject(  $this->methodNameEntityHydrator  );   
    }
    
    
    
    public function testHydrate() : void {   
        $name = "abcDEFěščř";
        $nameAfter = "setAbcDEFěščř";
        $hydrName = $this->methodNameEntityHydrator->hydrate($name);
        $this->assertEquals( $nameAfter,  $hydrName, "**CHYBA**při hydrataci");   
    }
    
    
    
    public function testExtract() : void {                  
        $name = "abcDEFěščř";
        $nameAfter = "getAbcDEFěščř";
        $extrName = $this->methodNameEntityHydrator->extract($name);
        $this->assertEquals( $nameAfter,  $extrName, "**CHYBA**při extrahovani");   
   
    }
    
    
}
