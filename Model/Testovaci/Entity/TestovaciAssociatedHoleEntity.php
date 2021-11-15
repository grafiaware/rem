<?php

namespace Model\Testovaci\Entity;

use Model\Testovaci\Entity\TestovaciAssociatedHoleEntityInterface;
use Model\Testovaci\Identity\TestovaciHoleIdentityInterface;
use Model\Entity\EntityAbstract;
use Model\Testovaci\Entity\TestovaciEntityInterface;

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
     * @var TestovaciEntityInterface
     */
    private $identityKralikaFk;
    
    
    /**
     * 
     * @param  $identity
     */
    public function __construct( TestovaciHoleIdentityInterface $identity, TestovaciEntityInterface $identityKralika ) {
        parent::__construct($identity);
        
        $this->identityKralikaFk = $identityKralika;
   
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
