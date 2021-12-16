<?php

namespace Model\Testovaci\Identity;

use Model\Entity\Identity\IdentityInterface;


/**
 *
 * @author vlse2610
 */
interface HoleIdentityInterface extends IdentityInterface {
    
    
    public function getId(): string ;
   
    public function setId(string $id): void ;
       
    
       
    public function getIndexFromIdentity() :string ;
}
