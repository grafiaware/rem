<?php
namespace Model\Entity\Identity\Key\Hydrator\NameHydrator;

use Model\Entity\Identity\Key\Hydrator\NameHydrator\HashNameHydratorInterface;


/**
 *
 * @author vlse2610
 */
class HashNameHydrator implements HashNameHydratorInterface {
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
