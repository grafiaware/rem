<?php

namespace Model\Entity;

use Model\Entity\Identity\IdentityInterface;

 
/**
 *
 * @author vlse2610
 */
interface EntityInterface {
    
    /**
     * 
     * @return IdentityInterface
     */
    public function getIdentity(): IdentityInterface;
    
    /**
     * 
     * @return void
     */
    public function setPersisted(): void;

    /**
     * 
     * @return void
     */
    public function setUnpersisted(): void;
    
    /**
     * 
     * @return bool
     */
    public function isPersisted(): bool;
}
