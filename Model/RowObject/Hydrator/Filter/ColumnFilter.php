<?php
namespace Model\RowObject\Hydrator\Filter ;

use Model\RowObject\Hydrator\Filter\ColumnFilterInterface;



/**
 * Description of ColumnFilter
 *
 * @author vlse2610
 */
    class ColumnFilter implements ColumnFilterInterface {
     /**
     * Filtr pro hydrator typu RowObjectHydrator
     * 
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
    
    /**
     * 
     * @return \Traversable
     */
    public function getIterator() : \Traversable {        
         return new \ArrayIterator( $this->poleJmen );
    }             


    

}
