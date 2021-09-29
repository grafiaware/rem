<?php

namespace Model\Testovaci\Identity;

use Model\Entity\Identity\IdentityAbstract;
use Model\Testovaci\Identity\TestovaciIdentityInterface;

/**
 * Description of TestovacIdentity
 *
 * @author vlse2610
 */
class TestovaciIdentity extends IdentityAbstract implements  TestovaciIdentityInterface {
    /**
     *
     * @var string
     */
    private $id1;
     /**
     *
     * @var string
     */
    private $id2;
    
    public function __construct( ) {

    }  
    
    
    public function getId1(): string {
        return $this->id1;
    }
    public function getId2(): string {
        return $this->id2;
    }

    public function setId1(string $id):TestovaciIdentity {
        $this->id1 = $id;
        return $this;
    }
     public function setId2(string $id):TestovaciIdentity {
        $this->id2 = $id;
        return $this;
    }

        
    
     public function getIndexFromIdentity() {
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
