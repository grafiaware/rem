<?php
//namespace Model\Entity;


/**
 * Description of KonfiguraceTestu 
 * 
 * @author vlse2610
 */
class KonfiguraceTestuEntity extends EntityAbstract {
    
    /**
     * @var string
     */
    private $uidKonfiguraceTestu;
    
    /**
     * @var string
     */
    private $popisTestu;
    
    /**
     * @var string
     */
    private $nazevTestu;
    
    /**
     * @var boolean
     */
    private $paralelVSessionSpustitelny;
    
    /**
     * @var SadaUlohEntity
     */
    private $sadaUloh;
    
    /**
     * @var boolean
     */
    private $valid;
  
    //-------------------------------------------------------------------------
    
    public function getUidKonfiguraceTestu() {
        return $this->uidKonfiguraceTestu;
    }

    public function getPopisTestu() {
        return $this->popisTestu;
    }

    public function getNazevTestu() {
        return $this->nazevTestu;
    }

    public function getParalelVSsessionSpustitelny() {
        return $this->paralelVSessionSpustitelny;
    }

    public function getSadaUloh(): SadaUlohDao {
        return $this->sadaUloh;
    }

    public function getValid() {
        return $this->valid;
    }

    public function setUidKonfiguraceTestu( $uidKonfiguraceTestu) {
        $this->uidKonfiguraceTestu = $uidKonfiguraceTestu;
        return $this;
    }

    public function setPopisTestu( $popisTestu ) {
        $this->popisTestu = $popisTestu;
        return $this;
    }

    public function setNazevTestu( $nazevTestu ) {
        $this->nazevTestu = $nazevTestu;
        return $this;
    }

    public function setParalelVSsessionSpustitelny( $paralelVSsessionSpustitelny) {
        $this->paralelVSessionSpustitelny = $paralelVSsessionSpustitelny;
        return $this;
    }

    public function setSadaUloh( SadaUlohEntity $sadaUloh) {
        $this->sadaUloh = $sadaUloh;
        return $this;
    }

    public function setValid($valid) {
        $this->valid = $valid;
        return $this;
    }


}
