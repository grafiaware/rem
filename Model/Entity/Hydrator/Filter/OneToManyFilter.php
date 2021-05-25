<?php
namespace Model\Entity\Hydrator\Filter;

use Model\Entity\Hydrator\Filter\OneToManyFilterInterface;




class OneToManyFilter implements OneToManyFilterInterface {
     /**
     * Filtr pro hydrator typu OneToMany (1 vlastnost entity To many vlastnosti rowobject) obsahuje seznam jmen pro vytvoreni jmena metody entity k hydrataci/extrakci 
     * a ke kazdemu jmenu prislusny seznam jmen  vlastností row objektu.
     * @var array
     */
    private $poleJmen;    
        
    /**
     * 
     * @param array $poleJmen
     */    
    public function __construct( array $poleJmen ) {
        $this->poleJmen = $poleJmen;        
    }

    
    
    //Pozn. - getIterator vrací iterovatelný objekt.    
    
    public function getIterator() : \Traversable {        
         return new \ArrayIterator( $this->poleJmen );
    }             


    
}
