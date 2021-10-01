<?php
namespace Model\RowObject\Key;

//use Model\RowObject\AttributeInterface;
use Model\RowObject\Key\KeyInterface;

/**
 * Description of KeyAbstract
 *
 * @author vlse2610
 */
abstract class KeyAbstract  implements KeyInterface {
    
    
  
    
    
    public function   getIndexFromKey() : string{
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
