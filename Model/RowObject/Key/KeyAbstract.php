<?php
namespace Model\RowObject\Key;

use Model\RowObject\AttributeInterface;
use Model\RowObject\Key\KeyInterface;

/**
 * Description of KeyAbstract
 *
 * @author vlse2610
 */
abstract class KeyAbstract  implements KeyInterface {
    /**
    * 
    * @param array $attribute
    */
    private $attribute;
    /**
    * 
    * @param array $generated
    */
    private $generated;
    
    
    
    public function __construct ( /*array $attribute  , */ array $generated ) {
        //$this->attribute = $attribute;        


        
//        // zda jména polí v $generated odpovídají $hash    
//        if (count( array_diff_key ($generated, $attribute)) != 0 ) {      
//            throw new IndexMismatchInKeyAttributtesException('Jména částí klíče v poli $generated  neodpovídají jménům v poli $attribute.');
//        }        
//        //zkontrolovat u pole generated naplneni pouze \True \FAlse
//        $okValue=true;
//        foreach ($generated as $value) {
//            if (!($value===true) and !($value===false)) {
//                $okValue = false;                 
//            }
//        }
//        if (!$okValue) {
//         throw new InvalidValueInGeneratedFieldException('Pole $generated obsahuje nepřípustné hodnoty.');
//        }             
        
        //$this->attribute = $attribute;
        $this->generated = $generated;
    }
    
          
    
    public function getIndexFromKeyRowObject() : array  {
        //get_object_vars - vybere ty "viditelne" a nestaticke
        
        foreach (get_obj_vars($this) as $nameAttr=>$value) {            
           $index =+ $value;                        
        }
        return $index;
    }
    
    
    
    
    
//    /**
//     *
//     * @var array assoc.pole - jméno prvku je generovaný klíč
//     */
//    public $generated;        
    
//    
//    public function getGenerated(): array {
//        
//    }
//    
//    public function setGenerated( array  $generated ): void {
//        $this->generated = $generated;
//    }
    
    
}
