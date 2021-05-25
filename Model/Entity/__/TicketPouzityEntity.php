<?php
namespace Model\Entity;

/**
 * Description of TicketPouzity
 *
 * @author vlse2610
 */
class TicketPouzityEntity extends EntityAbstract {
    /**
     * @var string 
     */         
    private $identifikatorTicketu;
    
    
    
    
    public function getIdentifikatorTicketu() {
        return $this->identifikatorTicketu;
    }

    public function setIdentifikatorTicketu($identifikatorTicketu) {
        $this->identifikatorTicketu = $identifikatorTicketu;
        return $this;
    }


    
   
}
