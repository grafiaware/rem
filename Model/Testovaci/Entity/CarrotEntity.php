<?php

namespace Model\Testovaci\Entity;

use Model\Entity\EntityAbstract;

use Model\Testovaci\Entity\CarrotEntityInterface;


/**
 * Description 
 *
 * @author vlse2610
 */
class CarrotEntity  extends EntityAbstract implements CarrotEntityInterface{
    /**
     *
     * @var integer
     */ 
    private $prumer;         
    
                
                     
    
    
    public function getPrumer() : int {
        return $this->prumer;
    }
    public function setPrumer( int $prumer): void  {
        $this->prumer = $prumer;
    }


     
}
