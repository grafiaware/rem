<?php
//namespace Model\Entity;

use Tester\Model\File\Entity\Uloha;


/**
 * Description of SadaUloh
 *
 * @author vlse2610
 */
class SadaUlohEntity extends EntityAbstract{        
    /**     
     * @var string
     */
    private $uidNazevSady;   //zde jmeno souboru bezpripony    
    
    
    //---mimon--------------------------------------
    /**
     * @var Uloha array of    
     */
    private $ulohy;   
    
    
    
    public function getUidNazevSady() {
        return $this->uidNazevSady;
    }

    public function getUlohy(): Uloha {
        return $this->ulohy;
    }

    public function setUidNazevSady($uidNazevSady) {
        $this->uidNazevSady = $uidNazevSady;
        return $this;
    }

    public function setUlohy(Uloha $ulohy) {
        $this->ulohy = $ulohy;
        return $this;
    }


    
    
    
}
