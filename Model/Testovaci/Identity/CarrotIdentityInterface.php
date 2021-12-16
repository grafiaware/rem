<?php

namespace Model\Testovaci\Identity;

/**
 *
 * @author vlse2610
 */
interface CarrotIdentityInterface extends IdentityInterface {
    
    
    public function getId(): string ;
   
    public function setId(string $id): void ;
       
    
       
    public function getIndexFromIdentity() : string;
}
