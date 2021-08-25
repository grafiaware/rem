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
     * Vrací TRUE, pokud hodnoty objektu byly změněny od jeho instancování nebo od posledního volání metody ->fetchChanged()
     * @return bool
     */
    public function isChanged(): bool;

    /**
     * Vrací asociativní pole hodnot změněných od instancování objektu nebo od posledního volání metody ->fetchChanged()
     *
     * Vrací pole změněných hodnot od instancování objektu nebo od posledního volání této metody.
     * Metoda vrací evidované změněné hodnoty a evidenci změněných hodnot smaže. Další změny jsou pak dále evidovány a příští volání
     * této metody vrací jen tyto další změny.
     *
     * @return array
     */
    public function fetchChanged(): array;

}
