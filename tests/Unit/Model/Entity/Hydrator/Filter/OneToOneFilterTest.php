<?php
namespace Test\OneToOneFilterTest;

use PHPUnit\Framework\TestCase;

use Model\Entity\Hydrator\Filter\OneToOneFilter;



/**
 * Description of OneToOneFilterTest
 *
 * @author vlse2610
 */
class OneToOneFilterTest extends TestCase {
    
   private $seznamJmen;
    
     /**
     * Před každým testem.
     * @return void
     */
    public function setUp(): void { 
        
        // - jmena vlastností (row objectu) (pro nastavení filtru),
        // jejichž hodnoty  budou hydratovat entitu/se budou extrahovat  z entity.   
        $this->seznamJmen = [ "prvekVarchar" ,  "prvekChar" ,  "prvekText" , "prvekInteger" ,  "prvekBoolean" , 
                              "prvekDate", "prvekDatetime", "prvekTimestamp" ];                                             
    }
    
    /**
     * 
     * @return void
     */
    public function testOneToOneFilter() : void {     
        
        $oneToOneFilter = new OneToOneFilter($this->seznamJmen);                                
        
        //testy
        $this->assertIsIterable( $oneToOneFilter, 'CHYBA-objekt neni iterable.');        
       
        foreach ($oneToOneFilter as $name) {
            $this->assertTrue( in_array($name, $this->seznamJmen) );    
        }
        
    }
    
}
