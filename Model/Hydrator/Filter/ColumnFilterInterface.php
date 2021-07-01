<?php
namespace Model\Hydrator\Filter;

/**
 *
 * @author vlse2610
 */
interface ColumnFilterInterface   extends \IteratorAggregate{
    
    
     /**    
     * Vrací jména, která budou použita.
     * @return \Traversable
     */
    public function getIterator() : \Traversable;
        
}
