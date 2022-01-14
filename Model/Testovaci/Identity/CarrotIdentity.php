<?php

namespace Model\Testovaci\Identity;

use Model\Entity\Identity\IdentityAbstract;
use Model\Testovaci\Identity\CarrotIdentityInterface;



class CarrotIdentity  extends IdentityAbstract implements CarrotIdentityInterface {
    /**
     *
     * @var string
     */
    private $id;
    
    
  
    
    public function __construct( ) {

    }
    
    
    public function getTypIdentity(): string{
        return CarrotIdentityInterface::class;
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
