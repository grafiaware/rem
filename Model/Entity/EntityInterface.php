<?php
namespace Model\Entity;

use Model\Entity\AccessorInterface;
use Model\Entity\Identity\IdentityInterface;
use Model\Entity\IdentitiesInterface;


/**
 *
 * @author vlse2610
 */
interface EntityInterface extends  AccessorInterface {
    

    
    /**
     * 
     * @return IdentitiesInterface
     */
    public function getIdentities(): IdentitiesInterface ;
    
    
    
    public function setPersisted(): void;
    
    public function setUnpersisted(): void ;
    
    public function isPersisted(): bool ;        
    
    
    public function lock(): void ;
    
    public function unLock(): void ;
    
    public function isLocked(): bool ;
    
}
