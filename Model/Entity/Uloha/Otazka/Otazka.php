<?php
namespace Model\Entity\Uloha\Otazka;

use Model\Entity\Uloha\EntityInterface;
use Model\Entity\Uloha\Otazka\Zadani\Zadani;

/**
 * Description of Otazka
 *
 * @author vlse2610
 */
class Otazka implements EntityInterface {
    /**
     * @var string 
     */
    private $legend;

    /**
    *
    * @var Zadani 
    */
    private $zadani;
    
    public function getLegend() {
        return $this->legend;
    }

    public function getZadani(): Zadani {
        return $this->zadani;
    }

    public function setLegend($legend) {
        $this->legend = $legend;
        return $this;
    }

    public function setZadani(Zadani $zadani) {
        $this->zadani = $zadani;
        return $this;
    }
}
