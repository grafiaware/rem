<?php
namespace Model\RowObject;

//use Model\RowObject\Key\KeyInterface;
use Model\RowObject\AttributeInterface;

/**
 * Description of RowObjectAbstract
 *
 * @author vlse2610
 */
abstract class RowObjectAbstract implements AttributeInterface {    
    /**
     *
     * @var KeyInterface
     */
    public $key ;
    
    public function __construct (AttributeInterface $key ) {
       $this->key = $key;        
    }
   
    
    
    
    
    
    
    
    
//private $keyHash;    
//    public function getKeyHash(): array {
//        $this->keyHash;
//    }
//    
//    public function setKeyHash(array $keyHash) {
//        $this->keyHash = $keyHash;
//    }
}
