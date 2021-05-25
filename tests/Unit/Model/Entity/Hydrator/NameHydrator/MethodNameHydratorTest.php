<?php
namespace Test\E_MethodNameEntityHydratorTest;

use PHPUnit\Framework\TestCase;

use Model\Entity\Hydrator\NameHydrator\MethodNameHydrator;
use Model\Entity\Hydrator\NameHydrator\MethodNameHydratorInterface;

/**
 * Description of MethodNameHydratorTest
 *
 * @author vlse2610
 */
class MethodNameEntityHydratorTest  extends TestCase {    
    /**
     *
     * @var MethodNameHydratorInterface
     */
    private $methodNameEntityHydrator;
    
    
    public function setUp(): void {   
        $this->methodNameEntityHydrator = new MethodNameHydrator( );  
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
