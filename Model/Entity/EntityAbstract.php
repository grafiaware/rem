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
    /**
     *
     * @var IdentityInterface 
     */
    private $identity;
    
    
    private $persisted=false;    
    private $locked=false;   
    
    

    public function __construct( IdentityInterface $identity ) {
        $this->identity = $identity;        
    }    
    
    public function getIdentity(): IdentityInterface {
        return $this->identity;
    }
//   NENE public function setIdentity( AccessorInterface $identity): void {
//        $this->identity = $identity;
//    }
      
    
    
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
