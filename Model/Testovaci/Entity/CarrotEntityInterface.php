<?php
namespace Model\Testovaci\Entity;

use \Model\Entity\EntityInterface;


/**
 *
 * @author vlse2610
 */
interface CarrotEntityInterface extends EntityInterface {
   
    public function getPrumer() : int ;    

    public function setPrumer( int $prumer): void  ;
   
    
    
}
