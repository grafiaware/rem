<?php
namespace Model\Entity;

use Model\Entity\EntityInterface;
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

    public function __construct( IdentityInterface $identity ) {
        $this->identity = $identity;        
    }    
    
    public function getIdentity(): IdentityInterface {
        return $this->identity;
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
 }
