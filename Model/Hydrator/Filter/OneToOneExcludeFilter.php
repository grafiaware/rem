<?php
namespace Model\Filter;

use Model\Hydrator\Filter\OneToOneExcludeFilterInterface;


class OneToOneExcludeFilter  implements OneToOneExcludeFilterInterface {
       
    /**
     * Seznam jmen vlastností vyloučených.
     * @var array
     */
    private $poleJmen;  
    /**
     * Seznam jmen vlastností všech.
     * @var array 
     */
    private $poleJmenVsechna;
        
    /**
     * 
     * @param array $poleJmen
     * @param array $poleJmenVsechna
     */    
    public function __construct( array $poleJmen, array $poleJmenVsechna  ) {  
        $this->poleJmen = $poleJmen;
        $this->poleJmenVsechna = $poleJmenVsechna;
    }
    
    
  
        
    
    
    //Pozn. - getIterator vrací iterovatelný objekt.    
    
    public function getIterator() : \Traversable{
        //...
         return new \ArrayIterator( $this->poleJmen );
    }
           
}
