<?php
namespace Model\RowObject;

use Model\RowObject\Key\KeyInterface;
use Model\RowObject\RowObjectInterface;

//use Model\RowObject\AttributeInterface;

/**
 * Description of RowObjectAbstract
 *
 * @author vlse2610
 */
abstract class RowObjectAbstract /*implements RowObjectInterface*/ {    
    /**
     *
     * @var KeyInterface
     */
    public $key ;    
    
    private $persisted=false;    
    private $locked=false;           
    
    
    public function __construct ( KeyInterface $key ) {
       $this->key = $key;        
    }
   
    
    
     public function getKey(): KeyInterface {
        return $this->key;
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
    
    
    public function isChanged(): bool {
        return false;
    }
    
    public function fetchChanged(): array {
        return [];
    }
    
  }  
    
    
    
    
    
    
    
    
//private $key;    
//    public function getKey(): array {
//        $this->keyHash;
//    }
//    
//    public function setKey(array $keyHash) {
//        $this->keyHash = $keyHash;
//    }

