<?php
namespace Model\Testovaci\Key;

use Model\Testovaci\Key;

use Model\RowObject\Key\KeyAbstract;
use Model\RowObject\Key\KeyInterface;

/**
 * Description of Key
 *
 * @author vlse2610
 */
class TestovaciKey extends KeyAbstract implements TestovaciKeyInterface {
    /**
     *
     * @var string
     */
    public $id1;
    /**
     *
     * @var string
     */
    public $id2;
    
    
    
     public function getIndexFromKey() {
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
