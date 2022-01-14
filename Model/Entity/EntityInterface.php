<?php
namespace Model\Entity;

use Model\Entity\AccessorInterface;
use Model\Entity\Identity\IdentityInterface;



/**
 *
 * @author vlse2610
 */
interface EntityInterface extends  AccessorInterface {
    

    
    /**
     * 
     * @return array
     */
    public function getIdentityNames(): array ;
    
  
    
    
    public function setPersisted(): void;
    
    public function setUnpersisted(): void ;
    
    public function isPersisted(): bool ;        
    
    
    public function lock(): void ;
    
    public function unLock(): void ;
    
    public function isLocked(): bool ;
    
}
