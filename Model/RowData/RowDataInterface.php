<?php

namespace Model\RowData;


/**
 *
 * @author pes2704
 */
interface RowDataInterface extends \IteratorAggregate, \ArrayAccess, \Serializable, \Countable { 
    
    const CODE_FOR_NULL_VALUE = 'special_string_for_NULL_value';

    // extenduje všechna rozhraní, která implementuje \ArrayObject mimo \Traversable - to nelze neb je prázdné
    
    /**
     * Vrací TRUE, pokud hodnoty objektu byly změněny od jeho instancování nebo od posledního volání metody ->deleteChanged()
     * @return bool
     */
    public function isChanged(): bool;
    
    /**
     * Vrací asociativní pole hodnot změněných od od instancování objektu nebo od posledního volání metody ->deleteChanged()
     * @return array
     */
    public function getChanged(): array;
    
    /**
     * Vrací pole indexů hodnot změněných od od instancování objektu nebo od posledního volání metody ->deleteChanged()
     * @return array
     */
    public function getIndexesOfChanged(): array ;
    /**
     * Odstraní hodnoty z pole hodnot změněných od od instancování objektu nebo od posledního volání metody ->deleteChanged()
     * @return void
     */
    public function deleteChanged(): void;
}
