<?php
namespace Model\Hydrator\NameHydrator;

use Model\Hydrator\NameHydrator\AccessorNameHydratorInterface;


/**
 * Description of NameHydrator
 *
 * @author vlse2610
 */
class AccessorNameHydrator implements AccessorNameHydratorInterface {
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
