<?php
//namespace Model\Entity\Identity\Key;
//use Model\Entity\Identity\Key\KeyInterface;

/**
 *
 * @author vlse2610
 */
interface KeyInterface {
    
//    /**
//     * Nastaví hodnoty klíče ($hash). Parametrem je asociativní pole, které musí mít stejné indexy jako atribut. 
//     * 
//     * @param array $hash Asociativní pole. Jednoprvkové pro simple key, n-prvkové pro compound (composite) key. Indexy musí odpovídat polím atributu.
//     * @throws \MismatchedIndexesToKeyAttributeFieldsException Pokud zadané pole má jiné indexy(jména čáístí klíče) než atributte.
//     */      
//    public function setHash(array $hash) :void;
    
    /**
     * Vrací asociativní pole (názvů a hodnot) částí atributu. 
     * 
     * @return array asociativní pole názvů/hodnot částí atributu
     */  
    public function getHash() : array ;

    /**
     * Vrací pole názvů částí atributu. 
     * 
     * @return array     
     */
    public function getAttribute();
  
//    /**
//     * Shodný atribut klíče (jednoduchý nebo kompozitní) - klíče mají shodná pole (sloupce), nezáleží na pořadí.     
//     * 
//     * @param KeyInterface $key
//     * @return bool
//     */
//    public function hasEqualAttribute( KeyInterface $key) :bool ;      
    
    
    /**
     * Shodné klíče - mají stejné páry index/hodnota, nezáleží na pořadí.
     *      
     * @param IdentityInterface $key
     * @return bool
     */
    public function isEqual(KeyInterface $key )  : bool ;
    
    
    
}