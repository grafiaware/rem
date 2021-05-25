<?php
namespace Model\Entity\Identity\Hydrator\NameHydrator;

use Model\Entity\Identity\Hydrator\NameHydrator\AttributeNameHydratorInterface;

/**
 *
 * @author vlse2610
 */
class AttributeNameHydrator implements AttributeNameHydratorInterface {
   /**
    * Z jmena pole atributu identity vyrobi jmeno atributu rowobjektu.
    * @param string $name
    * @return string
    */ 
   public function hydrate(string $name): string {
        return $name;
   }
   /**
    * Z jmena pole atributu identity vyrobi jmeno atributu rowobjektu. 
    * @param string $name
    * @return string
    */ 
   public function extract(string $name): string {
        return $name;
   }
}
