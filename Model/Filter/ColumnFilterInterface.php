<?php
namespace Model\Filter;

/**
 *
 * @author vlse2610
 */
interface ColumnFilterInterface   extends FilterInterface{
    
    
     /**    
     * Vrací jména, která budou použita.
     * @return \Traversable
     */
    public function getIterator() : \Traversable;
        
}
