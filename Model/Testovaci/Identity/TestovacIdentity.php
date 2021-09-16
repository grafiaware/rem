<?php

namespace Model\Testovaci\Identity;

use Model\Entity\Identity\IdentityAbstract;
use Model\Testovaci\Identity\TestovaciIdentityInterface;

/**
 * Description of TestovacIdentity
 *
 * @author vlse2610
 */
class TestovacIdentity extends IdentityAbstract implements  TestovaciIdentityInterface {
    /**
     *
     * @var string
     */
    private $id_1;
     /**
     *
     * @var string
     */
    private $id_2;
    
    
    
    
    public function getId(): string {
        return $this->id;
    }

    public function setId(string $id):TestovacIdentity {
        $this->id = $id;
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
