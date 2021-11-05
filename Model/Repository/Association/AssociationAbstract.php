<?php

namespace Model\Repository\Association;

use Model\Repository\Exception\UnableToCreateAssotiatedChildEntity;
use Model\RowObject\RowObjectInterface;

/**
 * Description of AssotiationAbstract
 *
 * 
 */
class AssociationAbstract {

    //protected $parentReferenceKeyAttribute;
    protected $childRepo;

    /**
     *
     * @param array $referenceKeyAttribute Atribut klíče, který je referencí na data rodiče v úložišti dat. V databázi jde o referenční cizí klíč.
     * HEPOTREBUJEME
     */
    public function __construct( /*$referenceKeyAttribute*/ ) {
        //$this->parentReferenceKeyAttribute = $referenceKeyAttribute;
    }

    public function flushChildRepo(): void {
        $this->childRepo->flush();
    }

    protected function getChildKey( RowObjectInterface $rowObject /* rodice*/    /*    $row*/) {
//        $parentKeyAttribute = $this->parentReferenceKeyAttribute;
//        if (is_array($parentKeyAttribute)) {
//            foreach ($parentKeyAttribute as $field) {
//                if( ! array_key_exists($field, $row)) {
//                    throw new UnableToCreateAssotiatedChildEntity("Nelze vytvořit asociovanou entitu.. Atribut referenčního klíče obsahuje pole $field a pole řádku dat pro vytvoření potomkovské entity neobsahuje prvek s takovým jménem.");
//                }
//                $childKey[$field] = $row[$field];
//            }
//        } else {
//            $childKey = $row[$this->parentReferenceKeyAttribute];
//        }
//        return $childKey;
        
        $rowObject->getKey();
        
        
    }

    /**
     *
     * @param string|array $key
     * @return type
     */
    protected function indexFromKey($key) {
        if (is_array($key)) {
            return implode(array_values($key));
        } else{
            return $key;
        }
    }
}
