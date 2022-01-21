<?php
namespace Model\Entity;

use Model\Entity\EntityInterface;

use Model\Entity\Identity\IdentityInterface;
use Model\Testovaci\Entity\Enum\IdentityTypeEnum;
use Model\Testovaci\Entity\Exception\IdentityTypeNotExistsInEntity;

use Pes\Type\Exception\ValueNotInEnumException;

/**
 * Description of TableEntityAbstract
 *
 * @author vlse2610
 */
abstract class EntityAbstract implements EntityInterface {
    /**
     *
     * @var \Traversable
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
     * @param \Traversable $identities
     * @param IdentityTypeEnum $enumIdentitiesNames
     */
    public function __construct( \Traversable $identities, IdentityTypeEnum $enumIdentitiesNames ) {              
        $this->identities =  $identities;   
        $this->enumIdentitiesNames = $enumIdentitiesNames;
        $identityTypes = $enumIdentitiesNames->getConstList();
        
        $identitiesArray = iterator_to_array($identities);
        if ($identityTypes!=$identitiesArray) {
            throw new MismatchedIdentities('Typy identit neodpovidaji.');
        }
        
        
        
        
//        // varianta a (s foreach)
//        foreach ($identityTypes as $key => $value) {
//            if (!array_key_exists($key, $identities)) {                
//            }
//        }
//        foreach ($identities as $key => $value) {
//            try {
//                $enumIdentitiesNames($key);
//            } catch (ValueNotInEnumException $exc) {
//                throw new IdentityTypeNotExistsInEntity("Fuj!!!");
//            }
//        }                
    }  
         
    
     /**
     * Vrací pole identit entity.
     * 
     * @return IdentityInterface[]
     */
    public function getIdentities(): \Traversable {
        return $this->identities;
    }
      
 
    /**
     * Vrátí identitu příslušného jména interface (typu identity).
     * 
     * @param string $identityInterfaceName
     * @return IdentityInterface
     * @throws IdentityTypeNotExistsInEntity
     */
    public function getIdentity(string $identityInterfaceName): IdentityInterface {
        try {
            $identityType = $this->enumIdentitiesNames($identityInterfaceName);
        } catch (ValueNotInEnumException $exc) {
            throw new IdentityTypeNotExistsInEntity("Typ identity $identityInterfaceName není v entitě.");
        } 
        return $this->identities [ $identityType];            
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
