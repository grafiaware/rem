<?php

namespace Aggregate\Models\Hydrator;

use Aggregate\Models\Relation\RelationKeyInterface;
use Aggregate\Models\Hydrator\NameHydratorInterface;

/**
 * Description of RelationKeyHydrator
 *
 * @author pes2704
 */
class KeyHydrator implements KeyHydratorInterface {

    /**
     * @var NameHydratorInterface
     */
    private $nameHydrator;

    /**
     * Konstruktor
     * Jako nepovinný parametr přijímá name hydrator - hydrátor pro překlad jmen polí. 
     * 
     * @param NameHydratorInterface $nameHydrator
     */
    public function __construct(NameHydratorInterface $nameHydrator) {
        $this->nameHydrator = $nameHydrator;
    }
    
    /**
     * Naplní hodnoty polí atributu klíče hodnotami z pole dat. Jména prvků pole dat se překládají na jména polí atributu klíče 
     * pomocí name hydrátoru zadaného v konstruktoru. Pokud v poli dat chybí požadovaný prvek (prvek s požadovaným indexem) metoda 
     * vyhodí výjimku. Pokud pole dat obsahuje i další prvky, nevadí to.
     * 
     * Metoda naplní klíč i v případě, že klíč je generovaný a běžným postupem nelze použít nastavení pomocí metody objektu klíče $key->setKeyHash($keyHash). 
     * Metoda v takovém případě používá reflexi.
     * 
     * @param RelationKeyInterface $key
     * @param \ArrayAccess $data Asociativní pole nebo např. RowData.
     * @return RelationKeyInterface Vrací zadaný klíč naplněný hodnotami z pole.
     * @throws \UnexpectedValueException Zadané pole neobsahuje hodnoty s potřebným indexem.
     */
    public function hydrate(RelationKeyInterface $key, \ArrayAccess $data) {
        $keyHash = array();
        foreach ($key->getKeyAttribute() as $attributeField) {
            $fkAttributeField = $this->nameHydrator->hydrateEntity($attributeField);
            if (!isset($fkAttributeField)) {
                throw new \UnexpectedValueException('Zadaný name hydrator negeneruje jméno sloupce pro atribut klíče '.$attributeField.'.');
            }
            if (isset($data[$fkAttributeField])) {
                $keyHash[$attributeField] = $data[$fkAttributeField];                
            } else {
                throw new \UnexpectedValueException('Zadaný hydrator negeneruje hodnotu ze sloupce '.$fkAttributeField.'.');
            }
        }
        $this->forceSetKeyHash($key, $keyHash);
    }
    
    private function forceSetKeyHash(RelationKeyInterface $key, $keyHash) {
        if ($key->isGenerated()) {
            $reflClass = new \ReflectionClass($key);
            $reflexPropKeyHash = $reflClass->getProperty('keyHash');
            $reflexPropKeyHash->setAccessible(TRUE);
            $reflexPropKeyHash->setValue($key, $keyHash);
            $reflexPropKeyHash->setAccessible(FALSE);
        } else {
            $key->setKeyHash($keyHash);
        }
        return $key;

    }


    /**
     * Extrahuje pole klíče jako páry jméno pole dat/hodnota do asociativního pole.Jména polí atributu klíče se překládají na  
     * jména prvků pole dat pomocí name hydrátoru zadaného v konstruktoru. 
     * Pokud v klíči chybí hodnota pro některé pole atributu metoda vyhodí výjimku.
     * 
     * Pokud je zadán parametr $data, metoda přidá nebo přepíše extrahované hodnoty do tohoto pole, jinak vytváří nové pole.
     * 
     * @param RelationKeyInterface $key
     * @param \ArrayAccess $data Asociativní pole nebo např. RowData
     */
    public function extract(RelationKeyInterface $key, \ArrayAccess $data) {
        foreach ($key->getKeyAttribute() as $field) {
            $fkAttributeField = $this->nameHydrator->extract($field);
            $value = $key->getKeyHash()[$field];
            if (isset($value)) {
                $data[$fkAttributeField] = $value;
            } else {
                throw new \UnexpectedValueException('Zadaný klíč neobsahuje potřebnou hodnotu s indexem '.$fkAttributeField.'. Nelze z něj naplnit datová pole odpovídající klíči.');
            }            
        }
    }
}
