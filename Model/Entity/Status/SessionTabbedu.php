<?php
namespace Model\Entity\Status;


/**
 * Entita SessionTabbedu- Má jednu public vlastnost $tabbedSessionData. 
 * Je určena k uložení serializovaného pole _Tabbed_container ukládaného do session QuickForm Tabbed kontrolérem.
 *
 * @author vlse2610
 */
class SessionTabbedu extends EntityAbstract   implements \Serializable  {
    /**
     *
     * @var type 
     */
   public $tabbedSessionData;
    
    
    
    public function serialize(): string {
        return serialize($this->tabbedSessionData);  //vraci "to" serializovane
    }
    
   
    public function unserialize ( /*string*/ $serialized) {
        $this->tabbedSessionData = unserialize($serialized);   // "to" odserializuje a da do promenne
    }
}
