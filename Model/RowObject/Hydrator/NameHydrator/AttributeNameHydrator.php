<?php
namespace Model\RowObject\Hydrator\NameHydrator;

use Model\RowObject\Hydrator\NameHydrator\NameHydratorInterface;

/**
 * Description of NameHydrator
 *
 * @author vlse2610
 */
class AttributeNameHydrator implements AttributeNameHydratorInterface {
    
    public function hydrate( /*$underscoredName*/  $camelCaseName){
        //return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $underscoredName))));
        return strtolower(preg_replace('/(?<!^)([A-Z])/', '_$1', $camelCaseName));
    }

    public function extract($camelCaseName) {                
       //$s2 = strtolower(preg_replace('/(?<!^)([A-Z])/', '_$1', $camelCaseName));        
       return strtolower(preg_replace('/(?<!^)([A-Z])/', '_$1', $camelCaseName));
}

}