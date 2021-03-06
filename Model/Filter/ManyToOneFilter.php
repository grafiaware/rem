<?php
namespace Model\Filter;

use Model\Filter\ManyToOneFilterInterface;



class ManyToOneFilter  implements ManyToOneFilterInterface{   
    
    /**
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
            
    
    //Pozn. getIterator vrací iterovatelný objekt.    
    
    public function getIterator() : \Traversable {       
         return new \ArrayIterator( $this->poleJmen );
    }
    

    
}
