<?php
namespace Model\Entity;

use Model\Entity\EntityInterface;

use Model\Entity\Identity\IdentityInterface;
use Model\Testovaci\Entity\Enum\IdentityTypeEnum;

use Pes\Type\Exception\ValueNotInEnumException;
use Model\Entity\IdentitiesInterface;
use Model\Entity\Exception\IdentityTypeNotExistsInEntity;


/**
 * Description of TableEntityAbstract
 *
 * @author vlse2610
 */
abstract class EntityAbstract implements EntityInterface {
    /**
     *
     * @var IdentitiesInterface
     */
    protected $identities;    
    /**
     *
     * @var IdentityTypeEnum
     */
    protected $enumIdentitiesNames;
    
    private $persisted=false;    
    private $locked=false;   
    
    
    
  
    /**
     * 
     * @param IdentitiesInterface $identities
     */
    public function __construct( IdentitiesInterface $identities) {              
        $this->identities =  $identities;         
    }  
    
    
    
         
    /**
     * Vrací objekt obsahující objekt Identities  (vlastnost entity) .
     * @return IdentitiesInterface
     */
    public function getIdentities(): IdentitiesInterface {
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
        $this->locked = true;
    }    
    public function unLock(): void {        
        $this->locked = false;
    }    
    public function isLocked(): bool {
        return $this->locked;                
    }
 }
