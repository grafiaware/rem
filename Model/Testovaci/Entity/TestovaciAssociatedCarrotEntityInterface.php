<?php
namespace Model\Testovaci\Entity;

use Model\Testovaci\Entity\TestovaciAssociatedCarrotEntityInterface;
use \Model\Entity\EntityInterface;



/**
 *
 * @author vlse2610
 */
interface TestovaciAssociatedCarrotEntityInterface extends EntityInterface {
       
    
    public function getPrumer() : integer ;    

    public function setPrumer($prumer): TestovaciAssociatedCarrotEntityInterface  ;
   
    
    
}
