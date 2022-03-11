<?php
namespace Model\Testovaci\Identity;

use Model\Entity\Identity\IdentityAbstract;
use Model\Testovaci\Identity\RabbitIdentityInterface;


/**
 * 
 */
class RabbitIdentity extends IdentityAbstract implements  RabbitIdentityInterface {
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
            
    
    public function getTypeIdentity(): string{
        return RabbitIdentityInterface::class;
    }
    
    
    public function getId1(): string {
        return $this->id1;
    }
    public function setId1(string $id): void {
        $this->id1 = $id;       
    }

   public function getId2(): string {
        return $this->id2;
    }
    public function setId2(string $id): void {
        $this->id2 = $id;       
    }
    
    
    
    
//    
//   private function getIndexFromIdentity() : string {
//        //get_object_vars - vybere ty "viditelne" a nestaticke
////        $index='';
////        foreach ( \get_object_vars($this) as $nameAttr=>$value) {            
////           $index =+ $value;                        
////        }
////        return $index;
//        
//        $a = \get_object_vars($this); 
//        $b = ksort ($a);
//        
//        $index="";
//        foreach ( $a  as $nameAttr=>$value ) {            
//           $index .= $value;                        
//        }
//        return $index;    
//        
//    }
    
        
}

