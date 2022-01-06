<?php
namespace Model\Entity;

use Model\Entity\AccessorInterface;
use Model\Entity\EntityInterface;

use Model\Entity\Identity;
use Model\Entity\Identity\IdentityInterface;

/**
 * Description of TableEntityAbstract
 *
 * @author vlse2610
 */
abstract class EntityAbstract implements EntityInterface {
  
//-------------->>>>>   identity budou vzdy v konkretni entite 
    
    
    
    private $identities;    //????
    
    
    private $persisted=false;    
    private $locked=false;   
    
    
    
    public function getIdentity( string $identityInterfaceName ): IdentityInterface {
        
    }

    
    public function getIdentities(): \Traversable {
        return $this->identities;
    }
    
    
    public function setPersisted(): void {
        $this->persisted = true;
    }
    
    public function setUnpersisted(): void {
        $this->persisted = false;
    }
    public function isPersisted(): bool {
        return $this->persisted;                
    }
        
    
    
    public function lock(): void {
        $this->identity->lock();
        $this->locked = true;
    }    
    public function unLock(): void {
        $this->identity->unlock();
        $this->locked = false;
    }    
    public function isLocked(): bool {
        return $this->locked;                
    }
 }
