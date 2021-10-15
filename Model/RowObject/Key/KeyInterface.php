<?php

namespace Model\RowObject\Key;

use Model\RowObject\AttributeInterface;

/**
 *
 * @author vlse2610
 */
interface KeyInterface  extends AttributeInterface{
    
       
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
    public function isEqual( KeyInterface $key ) : bool ;
    
    
    
   
    public function getIndexFromKey (): string;
     
}
