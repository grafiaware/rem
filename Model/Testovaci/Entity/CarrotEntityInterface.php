<?php
namespace Model\Testovaci\Entity;

use \Model\Entity\EntityInterface;


/**
 *
 * @author vlse2610
 */
interface CarrotEntityInterface extends EntityInterface {
    

    
    public function getPrumer() : integer ;    

    public function setPrumer($prumer): void  ;
   
    
    
}
