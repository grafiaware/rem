<?php
namespace Test\ManyToOneFilterTest;

use PHPUnit\Framework\TestCase;

use Model\Entity\Hydrator\Filter\ManyToOneFilter;


/**
 * Description of ManyToOneFilterTest
 *
 * @author vlse2610
 */
class ManyToOneFilterTest extends TestCase {   
    
   private $seznamJmen;
    
    /**
     * Před každým testem.
     * @return void
     */
    public function setUp(): void { 
        
        // - jmena  vlastností  (pro nastavení filtru), jejichž hodnoty  budou hydratovat entitu/se budou extrahovat z entity   
        // - jmena k vytvoreni názvů metod entity
        //  **** pole[ jméno pro vlastnost row objektu ] -> pole[] - jména pro metody entity ****
        $this->seznamJmen = [
            "celaAdresa" =>  ["ulice" , "cisloPopisne", "cisloOrientacni" , "mesto"],    
            "celaAdresaPrechodna" =>  ["ulice" , "cisloPopisne", "cisloOrientacni" , "mesto"],    
        ];                    
    }
    
    /**
     * 
     * @return void
     */
    public function testManyToOneFilter() : void {     
        
        $manyToOneFilter = new ManyToOneFilter( $this->seznamJmen);
        
        //testy
        $this->assertIsIterable( $manyToOneFilter, '*CHYBA* neni iterable.');        
       
        foreach ($manyToOneFilter as $propertyName => $methodNames) {
            $this->assertTrue( array_key_exists( $propertyName, $this->seznamJmen ) );
            $this->assertTrue( is_array($methodNames) );
            
            foreach ($methodNames as $methodName) {
                $this->assertTrue( in_array( $methodName, $this->seznamJmen[$propertyName] ) );
            }       
        }
       
    }
    
}