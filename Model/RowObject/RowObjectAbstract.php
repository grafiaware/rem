<?php
namespace Model\RowObject;

//use Model\RowObject\Key\KeyInterface;
use Model\RowObject\AttributeInterface;
use Model\RowObject\RowObjectInterface;

/**
 * Description of RowObjectAbstract
 *
 * @author vlse2610
 */
abstract class RowObjectAbstract implements RowObjectInterface  {    
    /**
     *
     * @var KeyInterface
     */
    public $key ;
    
    public function __construct (AttributeInterface $key ) {
       $this->key = $key;        
    }
   
    
    
    
    
    
    
    
    
//private $key;    
//    public function getKey(): array {
//        $this->keyHash;
//    }
//    
//    public function setKey(array $keyHash) {
//        $this->keyHash = $keyHash;
//    }
}
