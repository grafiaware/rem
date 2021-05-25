<?php
//namespace Model\Entity\Identity;
//
//use Model\Entity\Identity\Key\KeyInterface;
/**
 *
 * @author pes2704
 */
interface IdentityInterface  {
    
    /**
     * Vrací \TRUE  když klíč je generovaný.
     */
    public function hasGeneratedKey() : bool ;


    public function getKey(): KeyInterface;
    
    
    
    
    
//    /**
//     * Nastaví hodnotu klíče.
//     * 
//     * @param KeyInterface $key
//     * @return void
//     */
//    public function setKey(KeyInterface $key): void;
       
         
}



