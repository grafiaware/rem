<?php
namespace Test\OneToManyFilterTest;

use PHPUnit\Framework\TestCase;

use Model\Entity\Hydrator\Filter\OneToManyFilter;


/**
 * Description of OneToManyFilterTest
 *
 * @author vlse2610
 */
class OneToManyFilterTest extends TestCase {    
    
    private $seznamJmen;
    
    /**
     * Před každým testem.
     * @return void
     */
    public function setUp(): void { 
        
        // - jmena  vlastností  (pro nastavení filtru), jejichž hodnoty budou hydratovat entitu/se budou extrahovat z entity   
        // - jmena k vytvoreni názvů metod entity
        // **** pole[ jméno pro metodu entity ] -> pole[] - jména vlastnosti row objektu ****
        $this->seznamJmen = [
            "celeJmeno" =>  ["jmeno" , "prijmeni"],
            "celeJmenoDruhe" => ["jmeno" , "prijmeni"]
            ];            
        
    }
    
    /**
     * 
     * @return void
     */
    public function testOneToManyFilter() : void {     
        
        $oneToManyFilter = new OneToManyFilter($this->seznamJmen);
        
        //testy
        $this->assertIsIterable( $oneToManyFilter, 'CHYBA-objekt neni iterable.');        
       
        foreach ($oneToManyFilter as $methodName => $propertiesNames) {
            $this->assertTrue(array_key_exists($methodName, $this->seznamJmen));
            $this->assertTrue(is_array($propertiesNames));
            
            foreach ($propertiesNames as $propName) {
                $this->assertTrue(in_array($propName, $this->seznamJmen[$methodName]));
            }       
        }
       
    }
    
}