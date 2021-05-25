<?php

namespace Model\RowData;

/**
 *
 * @author pes2704
 */
trait RowDataTrait {

    private $changed;
    private $nulled;

    public function isChanged() {
        return count($this->changed) ? TRUE : FALSE;
    }

    public function getChanged() {
        return $this->changed;
    }

    public function deleteChanged() {
        unset($this->changed);
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
     * Stará data jsou, value je NULL -> nastavit  speciální hodnotuchanged = self::CODE_FOR_NULL_VALUE -> umožní provést zápis NULL do db = smazání sloupce
     *  tak, že v SQL INSERT musí být INSERT INTO tabulka (sloupec) VALUES (NULL) - NULL je klíčové (rezervované) slovo -> nemůžu je vkládat jako proměnnou s "hodnotou" NULL
     *  pak mám INSERT INTO tabulka (sloupec) VALUES () a to NULL nevyrobí
     * Stará data nejsou, metoda vrací hodnotu (ne NULL) -> nastavit changed=value
     * Stará data nejsou, metoda vrací NULL -> stará data nejsou protože je v db NULL nebo se sloupec v selectu nečetl -> v obou případech nedělat nic
     *
     * @param type $index
     * @param type $value
     */
    public function offsetSet($index, $value) {
//        if ($this->getChangedWasCalled) {
//            throw new LogicException('Již byla zavolána metoda getChanged() a data jsou zničena. Objekt nelze dále používat.');
//        }
        if (isset($value)) {
            // změněná nebo nová data
            if (parent::offsetExists($index) AND parent::offsetGet($index) != $value) {
                parent::offsetUnset($index);
                $this->changed[$index] = $value;
            } elseif (!parent::offsetExists($index)) {  // nová data nebo opakovaně měněná data
                $this->changed[$index] = $value;
            }
        } elseif (parent::offsetExists($index) AND parent::offsetGet($index) !== NULL) {
        // kontrola !== NULL je nutná, extract volá všechny settery a pokud vlastnost nebyla vůbec nastavena setter vrací NULL
        // musím kontrolavat, že data jsou NULL, ale původně nebyla - proto nelze volat offseUnset (ale data se neduplikují, v changed je jen konstanta)
            // smazat existující data
            $this->changed[$index] = self::CODE_FOR_NULL_VALUE;
        }
    }
}
