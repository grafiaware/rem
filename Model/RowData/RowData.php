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

    private $changed = [];

    /**
     * V kostruktoru se mastaví způsob zapisu dat do RowData objektu na ARRAY_AS_PROPS a pokud je zadán parametr $data, zapíší se tato data
     * do interní storage objektu. Nastavení ARRAY_AS_PROPS způsobí, že každý zápis dalších dat je prováděn metodou offsetSet.
     * @param type $data
     */
    public function __construct($data=[]) {
        parent::__construct($data, \ArrayObject::ARRAY_AS_PROPS);
    }

    public function isChanged(): bool {
        return ($this->changed) ? TRUE : FALSE;
    }

    /**
     * Vrací pole změněných hodnot od instancování objektu nebo od posledního volání této metody.
     * Metoda vrací evidované změněné hodnoty a evidenci změněných hodnot smaže. Další změny jsou pak dále evidovány a příští volání
     * této metody vrací jen tyto další změny.
     *
     * @return array
     */
    public function fetchChanged(): array {
        $ret = $this->changed;
        if ($this->isChanged()) {
            $this->changed = [];
        }
        return $ret;
    }

    public function offsetGet($index) {
        return parent::offsetGet($index);
    }

    public function offsetExists($index) {
        return parent::offsetExists($index);
    }

    public function exchangeArray($data) {
        // Zde by se musely v cyklu vyhodnocovat varianty byla/nebyla data x jsou/nejsou nová data
        throw new LogicException('Nelze použít metodu exchangeArray(). Použijte offsetSet().');
    }

    public function append($value) {
        throw new LogicException('Nelze vkládat neindexovaná data. Použijte offsetSet().');
    }

    /**
     * Ukládá data, která byla změněna po instancování. Metodě offsetSet nevadí, když je zavolána s hodnotou $value=NULL.
     * Postupuje takto:
     * Stará data jsou, metoda vrací jinou hodnotu -> unset data + nastavit changed=value
     * Stará data jsou, value je NULL -> nastavit  speciální hodnotu changed = self::CODE_FOR_NULL_VALUE -> umožní provést zápis NULL do db = smazání sloupce
     *  tak, že v SQL INSERT musí být INSERT INTO tabulka (sloupec) VALUES (NULL) - NULL je klíčová (rezervované) slovo -> nemůžu je vkládat jako proměnnou s "hodnotou" NULL
     *  pak mám INSERT INTO tabulka (sloupec) VALUES () a to NULL nevyrobí
     * Stará data nejsou, metoda vrací hodnotu (ne NULL) -> nastavit changed=value
     * Stará data nejsou, metoda vrací NULL -> stará data nejsou protože je v db NULL nebo se sloupec v selectu nečetl -> v obou případech nedělat nic
     *
     * @param type $index
     * @param type $value
     */
    public function offsetSet($index, $value) {
//        if (isset($value)) {
            // změněná nebo nová data
            if (!parent::offsetExists($index) OR parent::offsetGet($index) !== $value) {
                parent::offsetSet($index, $value);
                $this->changed[$index] = $value;
            }
//        }


//        if (isset($value)) {
//            // změněná nebo nová data
//            if (parent::offsetExists($index) AND parent::offsetGet($index) !== $value) {
//                parent::offsetSet($index, $value);
//                $this->changed[$index] = $value;
//            } elseif (!parent::offsetExists($index)) {  // nová data nebo opakovaně měněná data
//                $this->changed[$index] = $value;
//            }
//        } elseif (parent::offsetExists($index) AND parent::offsetGet($index) !== NULL) {
//        // kontrola !== NULL je nutná, extract volá všechny settery a pokud vlastnost nebyla vůbec nastavena setter vrací NULL
//        // musím kontrolavat, že data jsou NULL, ale původně nebyla - proto nelze volat offseUnset (ale data se neduplikují, v changed je jen konstanta)
//            // smazat existující data
//            parent::offsetSet($index, $value);
//            $this->changed[$index] = self::CODE_FOR_NULL_VALUE;
//        }
    }

}
