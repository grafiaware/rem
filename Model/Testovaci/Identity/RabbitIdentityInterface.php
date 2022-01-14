<?php
namespace Model\Testovaci\Identity;

use Model\Entity\Identity\IdentityInterface;


/**
 * 
 */
interface RabbitIdentityInterface extends IdentityInterface{
    
    
//    public function getTypIdentity(): string ;
    
    
    public function getId1(): string ;

    public function setId1(string $id): void ;
       
    
       
    public function getIndexFromIdentity() ;
    
}
