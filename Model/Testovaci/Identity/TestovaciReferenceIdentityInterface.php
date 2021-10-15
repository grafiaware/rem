<?php

namespace Model\Testovaci\Identity;

/**
 *
 * @author vlse2610
 */
interface TestovaciReferenceIdentityInterface extends IdentityInterface {
    
    
    public function getId(): string ;
   
    public function setId(string $id): TestovaciReferenceIdentityInterface ;
       
    
       
    public function getIndexFromIdentity() ;
}
