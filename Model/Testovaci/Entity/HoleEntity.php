<?php

namespace Model\Testovaci\Entity;

use Model\Entity\EntityAbstract;

use Model\Testovaci\Entity\HoleEntityInterface;

/**
 * Description 
 *
 * @author vlse2610
 */
class HoleEntity extends EntityAbstract implements HoleEntityInterface{
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
        
  
    
    
    public function getHloubka(): integer {
        return $this->hloubka;
    }

    public function getAdresa(): integer {
        return $this->adresa;
    }

    public function setHloubka(integer $hloubka) : void {
        $this->hloubka = $hloubka;
    }

    public function setAdresa(integer $adresa) : void {
        $this->adresa = $adresa;
    }




}
