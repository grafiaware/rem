<?php

namespace Model\Testovaci\Identity;

use Model\Testovaci\Identity\HoleIdentityInterface;
use Model\Entity\Identity\IdentityAbstract;

/**
 * 
 */
class HoleIdentity extends IdentityAbstract implements HoleIdentityInterface {
    /**
     *
     * @var string
     */
    private $id;
    
    public function __construct( ) {
    }
    
    public function getId(): string {
        return $this->id;
    }
   
    public function setId(string $id): void {
        $this->id = $id;        
    }
    
    
      public function getIndexFromIdentity() : string {
        //get_object_vars - vybere ty "viditelne" a nestaticke
//        $index='';
//        foreach ( \get_object_vars($this) as $nameAttr=>$value) {            
//           $index =+ $value;                        
//        }
//        return $index;
        
        $a = \get_object_vars($this); 
        $b = ksort ($a);
        
        $index="";
        foreach ( $a  as $nameAttr=>$value ) {            
           $index .= $value;                        
        }
        return $index;    
        
    }
    
    
}
