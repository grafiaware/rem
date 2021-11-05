<?php

namespace Model\Testovaci\Identity;

/**
 *
 * @author vlse2610
 */
interface TestovaciCarrotIdentityInterface extends IdentityInterface {
    
    
    public function getId(): string ;
   
    public function setId(string $id): TestovaciCarrotIdentityInterface ;
       
    
       
    public function getIndexFromIdentity() : string;
}
