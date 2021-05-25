<?php
namespace Model\Entity\Identity;

use Model\Entity\Identity\Key\KeyInterface;
/**
 *
 * @author pes2704
 */
interface IdentityInterface  {



    public function getKey(): KeyInterface;
    
    
    /**
     * 
     * @param KeyInterface $key
     * @return void
     */
    public function setKey( KeyInterface $key): void ;
        

       
         
}
    
//    /**
//     * Vrací \TRUE  když klíč je generovaný.
//     */
//    public function hasGeneratedKey() : bool ;


