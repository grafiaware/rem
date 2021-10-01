<?php
namespace Model\Testovaci\Key;

use Model\RowObject\Key\KeyInterface;


/**
 *
 * @author vlse2610
 */
interface TestovaciKeyInterface extends KeyInterface  {
    
    
    
    public function setHash( array $hash): void ;
        
    public function getHash(): array ;
    
    
    public function getGenerated(): array ;
     
    public function setGenerated( array  $generated ): void ;

    
 
//    public function getAttribute(): array ;

    
//    
//    /**    
//     * Shodný atribut klíče (jednoduchý nebo kompozitní) - atributy mají shodná pole (sloupce), nezáleží na pořadí.     
//     * 
//     * @param KeyInterface $key
//     * @return bool
//     */
//    public function hasEqualAttribute( KeyInterface $key ) : bool ;
    
    
    /**
     * Shodné klíče - mají stejné páry index/hodnota, nezáleží na pořadí.
     *      
     * @param KeyInterface $key
     * @return bool
     */
    public function isEqual( TestovaciKeyInterface $key ) : bool ;
    
    
    
    
    
    
}
