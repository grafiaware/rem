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
        
  
    
    
    public function getHloubka(): int {
        return $this->hloubka;
    }

    public function getAdresa(): int {
        return $this->adresa;
    }

    public function setHloubka(int $hloubka) : void {
        $this->hloubka = $hloubka;
    }

    public function setAdresa(int $adresa) : void {
        $this->adresa = $adresa;
    }




}
