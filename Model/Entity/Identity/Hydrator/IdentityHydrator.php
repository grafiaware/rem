<?php
namespace Model\Entity\Identity\Hydrator;

use Model\Entity\Identity\IdentityInterface;
use Model\Entity\Identity\Key\Key;
use Model\Entity\Identity\Key\KeyInterface;

use Model\Entity\Identity\Hydrator\IdentityHydratorInterface;
use Model\Entity\Identity\Hydrator\NameHydrator\AttributeNameHydratorInterface;

use Model\RowObject\RowObjectInterface;

use Model\Entity\Identity\Hydrator\Exception\MissingAttributeFieldValueException;
use Model\Entity\Identity\Hydrator\Exception\MissingPropertyRowObjectException;

/**
 * Description of IdentityHydrator
 *
 * @author vlse2610
 */
class IdentityHydrator implements IdentityHydratorInterface { 
    
//    /**
//     * @var AttributeNameHydratorInterface
//     */
//    private $nameHydrator;

    /**
     * 
     * Jako nepovinný parametr přijímá NameHydrator - hydrátor pro překlad jmen polí. 
     * 
     * @param AttributeNameHydratorInterface $nameHydrator
     */
    public function __construct(  /*AttributeNameHydratorInterface $nameHydrator = \NULL */   ) {
       // $this->nameHydrator = $nameHydrator;
    }
            
    
//    /**
//     * Naplní hodnoty polí atributu klíče objektu Identity hodnotami z row objectu. Jména atributů(vlastností) row objectu se překládají na jména polí atributu klíče 
//     * pomocí name hydrátoru zadaného v konstruktoru. Pokud row objectu chybí požadovaný atribut(vlastnost) metoda  vyhodí výjimku. 
//     * Pokud row object obsahuje i další atributy(vlastností), nevadí to.
//     * 
//     * Metoda naplní klíč i v případě, že klíč je generovaný a běžným postupem nelze použít nastavení pomocí metody objektu klíče $key->setKeyHash($keyHash). 
//     * Metoda v takovém případě používá reflexi.     
//     * 
//     * @param IdentityInterface $identity
//     * @param RowObjectInterface $rowObject
//     * @return void
//     * @throws MissingPropertyRowObjectException
//     */
   
    /**
     * Naplní hodnoty polí atributu klíče objektu Identity->key hodnotami z row objectu. Jména atributů(vlastností) row objectu se překládají na jména polí atributu klíče 
//   * pomocí name hydrátoru zadaného v konstruktoru. Pokud v row objectu chybí požadovaný atribut(vlastnost, metoda  vyhodí výjimku. 
//   * Pokud row object obsahuje i další atributy(vlastností), nevadí to.
//   * 
//   * Metoda naplní klíč i v případě, že klíč je generovaný a běžným postupem nelze použít nastavení pomocí metody objektu klíče $key->setHash($hash). 
//   * Metoda v takovém případě používá reflexi. 
     *
     * @param IdentityInterface $identity
     * @param RowObjectInterface $rowObject
     * @return void
     * @throws MissingPropertyRowObjectException
     */
    public function hydrate( IdentityInterface $identity, RowObjectInterface $rowObject): void {
//        $hash = array();
//        foreach ($identity->getKey()->getAttribute() as $attributeField) {            
//            if ($this->nameHydrator) {            
//                $rowObjectAttribute = $this->nameHydrator->hydrate($attributeField);               
//            } else { 
//                $rowObjectAttribute = $attributeField;                 
//            }                     
//            if (isset( $rowObject->$rowObjectAttribute  )) {
//                $hash[$attributeField] = $rowObject->$rowObjectAttribute ;       
//            } else {
//                //throw new \UnexpectedValueException("Zadaný row objekt nemá vlastnost $rowObjectAttribute získanou name hydratorem z jména pole atributu $attributeField.");       
//                throw new MissingPropertyRowObjectException("Zadaný row objekt nemá vlastnost $rowObjectAttribute získanou name hydratorem z jména pole atributu $attributeField.");       
//            }
//        }        
//        if ($identity->isGenerated()) { 
//            $this->forceSetKeyHash( $identity, $hash);   // TADY NEVIM CO SE DEJE
//        } else {
//            
//            $key = $identity->getKey();
//            $key->setHash($hash);      
//            $identity->setKey($key);
//        }
    }
    
    
    
    // TADY NEVIM CO SE DEJE
    private function forceSetKeyHash( IdentityInterface $identity, $keyHash): void {
        $reflClass = new \ReflectionClass($identity);
        $reflexPropKeyHash = $reflClass->getProperty('keyHash');
        $reflexPropKeyHash->setAccessible(TRUE);
        $reflexPropKeyHash->setValue($identity, $keyHash);
        $reflexPropKeyHash->setAccessible(FALSE);
    }


    /**
     * Extrahuje pole klíče jako páry 'jméno pole/hodnota' do row objectu. Jména polí atributu identity se překládají na  
     * jména vlastností (atributů) row objektu pomocí name hydrátoru zadaného v konstruktoru. 
     * Pokud v klíči chybí hodnota pro některou vlastnost (atribut) row objectu, metoda vyhodí výjimku.      
     * 
     * @param IdentityInterface $identity
     * @param RowObjectInterface $rowObject
     * @return void
     * @throws MissingAttributeFieldValueException
     */
    public function extract( IdentityInterface $identity, RowObjectInterface $rowObject): void {
        foreach ($identity->getKeyAttribute() as $attributeField) {
            if ($this->nameHydrator) {            
                $fieldName = $this->nameHydrator->extract($attributeField);                                       
            } else { 
                $fieldName = $attributeField;                 
            }
    
            $value = $identity->getKeyHash()[$attributeField];
            if (isset($value)) {
                $rowObject->$fieldName  = $value;
            } else {
                //throw new \UnexpectedValueException('Zadaný klíč neobsahuje potřebnou hodnotu s atributem '.$fieldName.'. Nelze z něj naplnit atribut(vlastnost) row objektu odpovídající poli klíče.');
                throw new MissingAttributeFieldValueException('Neexistuje hodnota pro pole klíče '.$fieldName ); //. Nelze  naplnit atribut(vlastnost) row objektu odpovídající poli klíče.
            }            
        }
    }
}



//
// public function hydrate( IdentityInterface $identity, RowObjectInterface $rowObject): void {
//        $keyHash = array();
//        foreach ($identity->getKeyAttribute() as $attributeField) {            
//            if ($this->nameHydrator) {            
//                $rowObjectAttribute = $this->nameHydrator->hydrate($attributeField);               
//            } else { 
//                $rowObjectAttribute = $attributeField;                 
//            }                     
//            if (isset( $rowObject->$rowObjectAttribute  )) {
//                $keyHash[$attributeField] = $rowObject->$rowObjectAttribute ;       
//            } else {
//                //throw new \UnexpectedValueException("Zadaný row objekt nemá vlastnost $rowObjectAttribute získanou name hydratorem z jména pole atributu $attributeField.");       
//                throw new MissingPropertyRowObjectException("Zadaný row objekt nemá vlastnost $rowObjectAttribute získanou name hydratorem z jména pole atributu $attributeField.");       
//            }
//        }        
//        if ($identity->isGenerated()) {
//            $this->forceSetKeyHash( $identity, $keyHash); 
//        } else {
//            $identity->setKey($keyHash);
//        }
//    }