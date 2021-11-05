<?php

namespace Model\Testovaci\Entity;

use Model\Testovaci\Entity\TestovaciAssociatedHoleEntityInterface;
use Model\Testovaci\Identity\TestovaciHoleIdentityInterface;
use Model\Entity\EntityAbstract;

/**
 * Description of TestovaciAssociatedHoleEntity
 *
 * @author vlse2610
 */
class TestovaciAssociatedHoleEntity extends EntityAbstract implements TestovaciAssociatedHoleEntityInterface{
    /**
     *
     * @var integer 
     */
    private $hloubka;
    /**
     *
     * @var integer 
     */
    private $adresa;
    
    /**
     * 
     * @param  $identity
     */
    public function __construct( TestovaciHoleIdentityInterface $identity ) {
        parent::__construct($identity);
    }  
         
    
    
    
    public function getHloubka(): integer {
        return $this->hloubka;
    }

    public function getAdresa(): integer {
        return $this->adresa;
    }

    public function setHloubka(integer $hloubka) : TestovaciAssociatedHoleEntityInterface {
        $this->hloubka = $hloubka;
        return $this;
    }

    public function setAdresa(integer $adresa) : TestovaciAssociatedHoleEntityInterface {
        $this->adresa = $adresa;
        return $this;
    }




}
