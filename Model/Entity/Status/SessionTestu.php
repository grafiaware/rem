<?php
namespace Model\Entity\Status;

/**
 * Description of SessionTestu
 *
 * @author vlse2610
 */
class SessionTestu {
    
    /**
     * Uchovávané informace o 'spustenem' = probihajicim testu, potřebné v příštím běhu skriptu (v jednom sezení).
     * Zapisují se do repository, v tomto případě session.
     */
    public $idDbEntityPrubehTestu;        
    
    public $testUkoncen = \NULL;
    
    public $testZahajen  = \NULL;
    

    
}
