<?php
namespace Model\Entity;

use Model\Entity\AccessorInterface;

/**
 * Description of TableEntityAbstract
 *
 * @author vlse2610
 */
abstract class EntityAbstract implements AccessorInterface {
    /**
     *
     * @var AccessorInterface 
     */
    private $identity;
    
    private $persisted=false;

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
 }
