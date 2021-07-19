<?php
namespace Model\Entity;

use Model\Entity\AccessorInterface;
use Model\Entity\EntityInterface;

/**
 * Description of TableEntityAbstract
 *
 * @author vlse2610
 */
abstract class EntityAbstract implements EntityInterface {
    /**
     *
     * @var AccessorInterface 
     */
    private $identity;
    
    private $persisted=false;
    
    private $locked=false;   
    
    

    public function __construct( AccessorInterface $identity ) {
        $this->identity = $identity;        
    }    
    
    public function getIdentity(): AccessorInterface {
        return $this->identity;
    }
//    public function setIdentity( AccessorInterface $identity): void {
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
        $this->locked = true;
    }    
    public function unLock(): void {
        $this->locked = false;
    }
    public function isLocked(): bool {
        return $this->locked;                
    }
 }
