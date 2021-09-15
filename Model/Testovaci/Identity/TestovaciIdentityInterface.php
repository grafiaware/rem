<?php
namespace Model\Testovaci\Identity;

use Model\Entity\Identity\IdentityInterface;
/**
 * Description of TestovaciIdentityInterface
 *
 * @author vlse2610
 */
interface TestovaciIdentityInterface extends IdentityInterface{
    
    public function getId(): string ;

    public function setId(string $id):TestovacIdentity ;
    
       
     public function getIndexFromIdentity() ;
    
}
