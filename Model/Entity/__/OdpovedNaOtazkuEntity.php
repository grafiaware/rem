<?php
//namespace Model\Entity;


/**
 *  Description of OdpovedNaOtazku
 * 
 * @author vlse2610
 */
class OdpovedNaOtazkuEntity extends EntityAbstract {
    
    /**     
     * @var int 
     */
    private $idOdpovedNaOtazku;
    
    /**    
     * @var PrubehTestuEntity
     */
    private $prubehTestu ; 
    
    /**
     * @var int 
     */
    private $identifikatorOdpovedi;
    
    /**
     * @var int
     */
    private $hodnota;
    
    //-------------------------------------------------------------------------
    
    public function getIdOdpovedNaOtazku() {
        return $this->idOdpovedNaOtazku;
    }

    public function getPrubehTestu(): PrubehTestuDao {
        return $this->prubehTestu;
    }

    public function getIdentifikatorOdpovedi() {
        return $this->identifikatorOdpovedi;
    }

    public function getHodnota() {
        return $this->hodnota;
    }

    public function setIdOdpovedNaOtazku( $idOdpovedNaOtazku) {
        $this->idOdpovedNaOtazku = $idOdpovedNaOtazku;
        return $this;
    }

    public function setPrubehTestu( PrubehTestuEntity $prubehTestu) {
        $this->prubehTestu = $prubehTestu;
        return $this;
    }

    public function setIdentifikatorOdpovedi( $identifikatorOdpovedi) {
        $this->identifikatorOdpovedi = $identifikatorOdpovedi;
        return $this;
    }

    public function setHodnota($hodnota) {
        $this->hodnota = $hodnota;
        return $this;
    }


    
    
}
