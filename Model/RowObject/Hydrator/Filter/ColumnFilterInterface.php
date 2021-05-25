<?php
namespace Model\RowObject\Hydrator\Filter ;

/**
 *
 * @author vlse2610
 */
interface ColumnFilterInterface {
    
    
     /**    
     * Vrací jména, která budou použita.
     * @return \Traversable
     */
    public function getIterator() : \Traversable;
    
    //put your code here
}
