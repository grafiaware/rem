<?php
namespace Model\Entity;

/**
 * Description of SvobodovaEntita
 *
 * @author vlse2610
 */
class SvobodovaEntita extends EntityAbstract implements SvobodovaEntitaInterface {
    private $kuk;
    private $buk;
    
    public function getKuk() {
        return $this->kuk;
    }

    public function getBuk() {
        return $this->buk;
    }

    public function setKuk($kuk) {
        $this->kuk = $kuk;
        return $this;
    }

    public function setBuk($buk) {
        $this->buk = $buk;
        return $this;
    }


}
