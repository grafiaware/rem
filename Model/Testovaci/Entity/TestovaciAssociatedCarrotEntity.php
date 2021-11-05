<?php

namespace Model\Testovaci\Entity;

use Model\Testovaci\Entity\TestovaciAssociatedCarrotEntityInterface;
use Model\Entity\EntityAbstract;
use Model\Testovaci\Identity\TestovaciCarrotIdentityInterface;


/**
 * Description of TestovaciAssociatedCarror
 *
 * @author vlse2610
 */
class TestovaciAssociatedCarrotEntity  extends EntityAbstract implements TestovaciAssociatedCarrotEntityInterface{
    /**
     *
     * @var integer
     */ 
    private $prumer;
    
    /**
     * 
     * @param  $identity
     */
    public function __construct( TestovaciCarrotIdentityInterface $identity ) {
        parent::__construct($identity);
    }  
         
    
    public function getPrumer() : integer {
        return $this->prumer;
    }

    public function setPrumer($prumer): TestovaciAssociatedCarrotEntityInterface  {
        $this->prumer = $prumer;
        return $this;
    }


     
}
