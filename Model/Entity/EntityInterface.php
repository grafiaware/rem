<?php
namespace Model\Entity;

use Model\Entity\AccessorInterface;



/**
 *
 * @author vlse2610
 */
interface EntityInterface extends  AccessorInterface {
    
    /**
     * Vrací identitu požadovaného typu.
     * @return IdentityInterface 
     */
    public function getIdentity( string $identityInterfaceName ): IdentityInterface ; 
    
    /**
     * Vrací všechny identity.
     * @return \Traversable
     */
    public function getIdentities(): \Traversable ;
    
    
    public function setPersisted(): void;
    
    public function setUnpersisted(): void ;
    
    public function isPersisted(): bool ;        
    
    
    public function lock(): void ;
    
    public function unLock(): void ;
    
    public function isLocked(): bool ;
    
}
