<?php
namespace Model\Testovaci\Identity;

use Model\Entity\Identity\IdentityInterface;
use Model\Testovaci\Identity\TestovaciIdentityInterface;

/**
 * Description of TestovaciIdentityInterface
 *
 * @author vlse2610
 */
interface TestovaciIdentityInterface extends IdentityInterface{
    
    
    
    public function getId1(): string ;
    public function getId2(): string ;

    public function setId1(string $id): TestovaciIdentityInterface ;
    public function setId2(string $id): TestovaciIdentityInterface ;
       
    
       
    public function getIndexFromIdentity() ;
    
}
