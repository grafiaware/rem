<?php
namespace Model\Testovaci\Entity;

use Model\Testovaci\Identity\CarrotIdentityInterface;
use Model\Testovaci\Identity\RabbitIdentityInterface;
use \Model\Entity\EntityInterface;



/**
 *
 * @author vlse2610
 */
interface CarrotEntityInterface extends EntityInterface {
    
   
    public function getCarrotIdentity() : CarrotIdentityInterface ;
    
    public function getRabbitIdentityFk() : RabbitIdentityInterface ;
    
    
    public function getPrumer() : integer ;    

    public function setPrumer($prumer): void  ;
   
    
    
}
