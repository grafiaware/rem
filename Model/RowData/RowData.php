<?php

namespace Model\RowData;

/**
 * Description of RowData
 * Objekt reprezentuje položku relace (řádek dat db tabulky). Výchozí data se nastaví jako instatnční proměnná. Změněná a nová data
 * jsou objektem ukládána pouze, pokud se liší proti výchozím datům. Objekt pak poskytuje metody pro vrácení pouze změněných a nových položek dat pro účel zápisu do uložiště.
 *
 * @author pes2704
 */
class RowData extends \ArrayObject implements RowDataInterface {

    use RowDataTrait;

    /**
     * V kostruktoru se mastaví způsob zapisu dat do RowData objektu na ARRAY_AS_PROPS a pokud je zadán parametr $data, zapíší se tato data
     * do interní storage objektu. Nastavení ARRAY_AS_PROPS způsobí, že každý zápis dalších dat je prováděn metodou offsetSet.
     * @param type $data
     */
    public function __construct($data=[]) {
        parent::__construct($data, \ArrayObject::ARRAY_AS_PROPS);
    }
}
