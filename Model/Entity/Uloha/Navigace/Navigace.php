<?php
namespace Model\Entity\Uloha\Navigace;

use Model\Entity\Uloha\EntityInterface;
/**
 * Description of Navigace
 *
 * @author vlse2610
 */
class Navigace implements EntityInterface{
    /**
     * @var string 
     */
    private $napis;
    
    public function getNapis() {
        return $this->napis;
    }

    public function setNapis($napis) {
        $this->napis = $napis;
        return $this;
    }


}
