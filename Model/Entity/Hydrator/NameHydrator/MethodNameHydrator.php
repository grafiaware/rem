<?php
namespace Model\Entity\Hydrator\NameHydrator;

use Model\Entity\Hydrator\NameHydrator\MethodNameHydratorInterface;
    

/**
 * Jmena metod hydrate() a extract() jsou v NameHydratoru (kazdem)  pouze rozlisovaci,
 * a znamenaji pouze, ze metody NameHydratoru se pouzivaji pri hydratovani a extractovani objektu.
 * Obsahem metod je prevod jmen (..at uz to znamena cokoli..).
 * 
 * @author vlse2610
 */
class MethodNameHydrator implements MethodNameHydratorInterface {
    
    public function hydrate(string $name): string {
        return 'set' .  ucfirst( $name );
    }
    
    public function extract(string $name): string {       
        return 'get' .  ucfirst( $name );
    }
    
}
