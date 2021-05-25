<?php
namespace Model\Entity\Hydrator\NameHydrator;

use Model\Entity\Hydrator\NameHydrator\AttributeNameHydratorInterface;


/**
 * Description of NameHydrator
 *
 * @author vlse2610
 */
class AttributeNameHydrator implements AttributeNameHydratorInterface {
    /**     
     * @param string $name
     * @return string
     */
    public function hydrate( string $name ) : string {   
        return $name  ;
        
    }
    
    /**
     * 
     * @param string $name
     * @return string
     */
    public function extract( string $name )  : string {                
       return  $name  ;        
                  
    }
}
