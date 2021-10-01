<?php
namespace Model\Testovaci\Key;

use Model\Testovaci\Key;
use Model\Testovaci\Key\TestovaciKeyInterface;

use Model\RowObject\Key\KeyAbstract;
use Model\RowObject\Key\KeyInterface;

/**
 * Description of Key
 *
 * @author vlse2610
 */
class TestovaciKey extends KeyAbstract implements TestovaciKeyInterface {
    /**
     *
     * @var string
     */
    public $id1;
    /**
     *
     * @var string
     */
    public $id2;
    
    //-------------------------
    
    
    
       
    /**
     * Pole, které jako hodnoty má názvy(jména) polí částí klíče v asoc. poli hash.
     * @param array $attribute
     */
    private $attribute;   
    
    /**
     * Klíč - asoc.pole dvojic (KeyValue pair) jméno(části klíče) -> hodnota(části klíče).
     * @var array 
     */
    private $hash;  
    /**
     * Asoc.pole dvojic -  jméno(části klíče) -> hodnota TRUE|FALSE (část klíče je|není generovaná).
     * @var array
     */
    private $generated;
    
    
    /**
     * V konstruktoru nastaví vlastnosti objektu Key.
     *     
     */    
    public function __construct (    /*array $hash  , array $generated */  ) {
        
        
//        //$this->attribute = $attribute;                
//        // zda jména polí v $generated odpovídají $hash    
//        if (count( array_diff_key ($generated, $hash)) != 0 ) {      
//            throw new IndexMismatchInKeyAttributtesException('Jména částí klíče v poli $generated  neodpovídají jménům v poli $hash.');
//        }        
//        //zkontrolovat u pole generated naplneni pouze \True \FAlse
//        $okValue=true;
//        foreach ($generated as $value) {
//            if (!($value===true) and !($value===false)) {
//                $okValue = false;                 
//            }
//        }
//        if (!$okValue) {
//         throw new InvalidValueInGeneratedFieldException('Pole $generated obsahuje nepřípustné hodnoty.');
//        }                     
//        $this->hash = $hash;
//        $this->generated = $generated;
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
            throw new  AttemptToSetGeneratedKeyException('Klíč má generované části. Hodnoty generovaného klíče lze nastavit pouze hydrátorem při čtení z databáze.');                  
        }                
        if (count( array_diff_key ( $this->generated, $hash )) != 0 ) {      
            throw new IndexMismatchInKeyAttributtesException('Jména částí nastavovaného klíče v poli $hash neodpovidaji jménům v poli $generated objektu Key.');
        }
        
        $this->hash = $hash;
    }

    public function getHash(): array {
       return $this->hash;
    }


    
    public function getGenerated(): array {
       return $this->generated;
    }
    public function setGenerated( array  $generated ): void {
        $this->generated = $generated;
    }
//     
//    public function getAttribute(): array {
//        return $this->attribute;
//    }      
    
    
//-------------------------------------------------------------------    
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
     * @param KeyInterface $key
     * @return bool
     */
    public function isEqual(TestovaciKeyInterface $key ) : bool {
        //$a == $b 	Equality 	TRUE if $a and $b have the same key/value pairs. - nezáleží na pořadí - testováno
        //$a === $b 	Identity 	TRUE if $a and $b have the same key/value pairs in the same order and of the same types.)
        return $this->hash == $key->getHash();
    }
  
}
