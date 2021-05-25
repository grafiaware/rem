<?php
namespace Model\Entity\Identity\Key;

use Model\Entity\Identity\Key\Exception\MismatchedIndexesToKeyAttributeFieldsException;
use Model\Entity\Identity\Exception\AttemptToSetGeneratedKeyException;

/**
 * Description of Key
 *
 * @author vlse2610
 */
class Key implements KeyInterface {
   
//    /**
//     * Pole, které jako hodnoty má názvy(jména) polí částí klíče v asoc. poli hash.
//     * @param array $attribute
//     */
//    private $attribute;    
    /**
     * Klíč - asoc.pole dvojic (KeyValue pair) jmeno(casti klice)->hodnota(casti klice).
     * @var array 
     */
    private $hash;  
    
    private $generated;
    
    
    /**
     * V konstruktoru nastaví ... objektu Key.
     *     
     */    
    public function __construct ( array $hash  , array $generated ) {
        //$this->attribute = $attribute;
        $this->hash = $hash;
        $this->generated = $generated;
        
        //  !!!!!!!!!!!!!!!!!!!!!!!    zkontrolovat rozmer pole generated a naplneni pouze \True \FAlse
    }
            
    
//    /**
//     * Nastaví hodnoty klíče (hash). Parametrem je asociativní pole, které musí mít stejné indexy jako attribute.
//     *  
//     * @param array $hash Asociativní pole. Jednoprvkové pro simple key, n-prvkové pro compound (composite) key. Indexy musí odpovídat polím atributu.
//     * @return $this 
//     * @throws \MismatchedIndexesToKeyAttributeFieldsException Pokud zadané pole má jiné indexy(jména čáístí klíče) než atributte.
//     */      
//    public function setHash( array $hash ) :void {        
//        
//        if( $this->attribute != array_keys($hash) ) {
//            throw new MismatchedIndexesToKeyAttributeFieldsException('Jména částí klíče ($hash) neodpovídají polím atributu klíče zadaným v konstruktoru nebo jsou v jiném pořadí.');
//        }
//        $this->hash = $hash;
//        //return $this;
//    }
    
    
    
    public function setHash( array $hash): void {
        
        if (in_array ( \TRUE, $this->getGenerated()) ) {
        //if ($this->hasGeneratedKey) {
            throw new  AttemptToSetGeneratedKeyException('Klíč má generované části. Hodnoty generovaného klíče lze nastavit pouze hydrátorem při čtení z databáze.');           
            //throw new \LogicException('Klíč je generovaný a hodnoty generovaného klíče lze nastavit pouze hydrátorem při čtení z databáze.');
        }
        $this->hash = $hash;
    }

    
    public function getHash(): array {
       return $this->hash;
    }
    
    public function getGenerated(): array {
       return $this->generated;
    }

    
//     
//    public function getAttribute(): array {
//        return $this->attribute;
//    }      
    
    
    
//    
//    /**    
//     * Shodný atribut klíče (jednoduchý nebo kompozitní) - atributy mají shodná pole (sloupce), nezáleží na pořadí.     
//     * 
//     * @param KeyInterface $key
//     * @return bool
//     */
//    public function hasEqualAttribute( KeyInterface $key ) : bool {
//        return $this->attribute == $key->getAttribute();
//    }
    
    /**
     * Shodné klíče - mají stejné páry index/hodnota, nezáleží na pořadí.
     *      
     * @param IdentityInterface $key
     * @return bool
     */
    public function isEqual(KeyInterface $key ) : bool {
        //$a == $b 	Equality 	TRUE if $a and $b have the same key/value pairs. - nezáleží na pořadí - testováno
        //$a === $b 	Identity 	TRUE if $a and $b have the same key/value pairs in the same order and of the same types.)
        return $this->hash == $key->getHash();
    }
}
