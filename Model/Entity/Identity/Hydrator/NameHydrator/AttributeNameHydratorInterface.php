<?php
namespace Model\Entity\Identity\Hydrator\NameHydrator;

/**
 * Jmena metod hydrate() a extract() jsou v NameHydratoru (kazdem)  pouze rozlisovaci,
 * a znamenaji pouze, ze metody NameHydratoru se pouzivaji pri hydratovani a extractovani objektu.
 * Obsahem metod je prevod jmen (..at uz to znamena cokoli.. ).
 * 
 * @author vlse2610
 */
interface AttributeNameHydratorInterface {
   
    public function hydrate(string $name): string;
    
    public function extract(string $name): string;
    
}
