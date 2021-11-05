<?php

namespace Model\Testovaci\Identity;

use Model\Entity\Identity\IdentityInterface;


/**
 *
 * @author vlse2610
 */
interface TestovaciHoleIdentityInterface extends IdentityInterface {
    
    
    public function getId(): string ;
   
    public function setId(string $id): TestovaciHoleIdentityInterface ;
       
    
       
    public function getIndexFromIdentity() :string ;
}
