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
class TestovaciAssociatedCarrorEntity  extends EntityAbstract implements TestovaciAssociatedCarrotEntityInterface{
     
    private $prumer;
    
    /**
     * 
     * @param  $identity
     */
    public function __construct( TestovaciCarrotIdentityInterface $identity ) {
        parent::__construct($identity);
    }  
         
     
}
