<?php
namespace Model\Entity\Identity;

use Model\Entity\AccessorInterface;

/**
 *
 * @author vlse2610
 */
interface   IdentityInterface  extends AccessorInterface{
    
    public function getTypeIdentity(): string ;
    
    
    
    
    
    
    
//    public function lock(): void ;    
//    public function unlock(): void ;      
//    public function isLocked(): bool ;
              
    
}