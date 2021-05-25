<?php
namespace Model\Entity\Key\Hydrator; 

use Model\Entity\Identity\Key\KeyInterface;
use Model\RowObject\RowObjectInterface;
use Model\Entity\Identity\Key\Hydrator\NameHydrator\HashNameHydratorInterface;



/**
 * Description of KeyHydrator
 *
 * @author vlse2610
 */
class KeyHydrator implements KeyHydratorInterface{
    
    private $nameHydrator;    
    
    public function __construct(  HashNameHydratorInterface $nameHydrator = \NULL ) {
        $this->nameHydrator = $nameHydrator;
    }        
    
    
   /**
     * 
     * @param KeyInterface $key
     * @param RowObjectInterface $rowObject
     * @return void
     */
    public function hydrate( KeyInterface $key, RowObjectInterface $rowObject): void {
        
        $hash = array();
        foreach ($key->getKeyAttribute() as $attributeField) {            
            if ($this->nameHydrator) {            
                $rowObjectAttribute = $this->nameHydrator->hydrate($attributeField);               
            } else { 
                $rowObjectAttribute = $attributeField;                 
            }                     
            if (isset( $rowObject->$rowObjectAttribute  )) {
                $hash[$attributeField] = $rowObject->$rowObjectAttribute ;       
            } else {
                //throw new \UnexpectedValueException("Zadaný row objekt nemá vlastnost $rowObjectAttribute získanou name hydratorem z jména pole atributu $attributeField.");       
                throw new MissingPropertyRowObjectException("Zadaný row objekt nemá vlastnost $rowObjectAttribute získanou name hydratorem z jména  $attributeField.");       
            }                      
        }    
        
        
//        if ( $key->getGenerated[$attributeField]  ) {
//                $this->forceSetKeyHash( $key, $attributeField, $hash[$attributeField] ); 
//            } else {
//                $key->setHash($keyHash);
//            }        
        
        
        
        
        
    }
     
    private function forceSetKeyHash( IdentityInterface $key,  $keyHashName, $keyHashValue): void {
        $reflClass = new \ReflectionClass($key);
        $reflexPropKeyHash = $reflClass->getProperty('hash');
        $reflexPropKeyHash->setAccessible(TRUE);
        //<<<<<<<<??????????????
        $reflexPropKeyHash->setValue( $reflexPropKeyHash[$keyHashName], $keyHashValue);  //<<<<<<<<??????????????
        //<<<<<<<<??????????????
        $reflexPropKeyHash->setAccessible(FALSE);
    }
    
//    private function forceSetKeyHash( IdentityInterface $identity, $keyHash): void {
//        $reflClass = new \ReflectionClass($identity);
//        $reflexPropKeyHash = $reflClass->getProperty('keyHash');
//        $reflexPropKeyHash->setAccessible(TRUE);
//        $reflexPropKeyHash->setValue($key, $keyHash);
//        $reflexPropKeyHash->setAccessible(FALSE);
//    }
    

    
    /**
     * 
     * @param KeyInterface $key
     * @param RowObjectInterface $rowObject
     * @return void
     */ 
    public function extract(  KeyInterface $key, RowObjectInterface $rowObject): void {
        
    }
        
    
    
}
