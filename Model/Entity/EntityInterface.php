<?php
namespace Model\Entity;

/**
 *
 * @author vlse2610
 */
interface EntityInterface extends  AccessorInterface {
    
    public function getIdentity(): AccessorInterface ;           
    
    public function setPersisted(): void;
    
    public function setUnpersisted(): void ;
    
    public function isPersisted(): bool ;        
    
    public function lock(): void ;
    
    public function unLock(): void ;
    
    public function isLocked(): bool ;
    
}
