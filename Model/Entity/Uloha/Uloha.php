<?php
namespace Model\Entity\Uloha;

use Model\Entity\Uloha\EntityInterface;
use Model\Entity\Uloha\Navigace\Navigace;
use Model\Entity\Uloha\Otazka\Otazka;

class Uloha implements EntityInterface {
        
    /**
     *
     * @var Navigace
     */
    private $navigace;
    /**
     *
     * @var Otazka
     */
    private $otazka;
    
    /**
     * @return Navigace
     */
    public function getNavigace(): Navigace {
        return $this->navigace;
    }

    /**
     * @return Otazka
     */
    public function getOtazka(): Otazka {
        return $this->otazka;
    }

    public function setNavigace( Navigace $navigace) {
        $this->navigace = $navigace;
        return $this;
    }

    public function setOtazka(Otazka $otazka) {
        $this->otazka = $otazka;
        return $this;
    }
}

