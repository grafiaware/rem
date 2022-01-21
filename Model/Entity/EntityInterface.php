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
     * Vrací pole identit entity.
     * 
     * @return IdentityInterface[]
     */
    public function getIdentities(): \Traversable ;
    
    /**
     * Vrátí identitu příslušného jména interface (typu identity).
     * 
     * @param string $identityInterfaceName
     * @return IdentityInterface
     */
    public function getIdentity( string $identityInterfaceName) : IdentityInterface;
    
    
    
    
    public function setPersisted(): void;
    
    public function setUnpersisted(): void ;
    
    public function isPersisted(): bool ;        
    
    
    public function lock(): void ;
    
    public function unLock(): void ;
    
    public function isLocked(): bool ;
    
}
