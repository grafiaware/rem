<?php
namespace Model\Testovaci\Entity;

use Model\Entity\EntityInterface;
use Model\Testovaci\Identity\RabbitIdentityInterface;

/**
 *
 * @author vlse2610
 */
//interface TestovaciAssociatedHoleEntityInterface extends EntityInterface {
interface HoleEntityInterface extends EntityInterface {
    
  
    public function getHoleIdentity() : HoleIdentityInterface ;
    
    public function getRabbitIdentityFk() : RabbitIdentityInterface ;
    
    
    public function getHloubka(): integer ;

    public function getAdresa(): integer ;

    public function setHloubka(integer $hloubka) : void ;

    public function setAdresa(integer $adresa) : void ;

}
