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
    
                
                     
    
    
    public function getPrumer() : integer {
        return $this->prumer;
    }
    public function setPrumer($prumer): void  {
        $this->prumer = $prumer;
    }


     
}
