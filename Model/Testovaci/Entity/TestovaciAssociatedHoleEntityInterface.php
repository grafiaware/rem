<?php
namespace Model\Testovaci\Entity;

use \Model\Entity\EntityInterface;

/**
 *
 * @author vlse2610
 */
interface TestovaciAssociatedHoleEntityInterface extends EntityInterface {
  
    public function getHloubka(): integer ;

    public function getAdresa(): integer ;

    public function setHloubka(integer $hloubka) : TestovaciAssociatedHoleEntityInterface ;

    public function setAdresa(integer $adresa) : TestovaciAssociatedHoleEntityInterface ;

}
