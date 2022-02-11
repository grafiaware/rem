<?php
namespace Model\Testovaci\Entity;

use Model\Entity\EntityInterface;

/**
 *
 * @author vlse2610
 */
interface HoleEntityInterface extends EntityInterface {
 
    
    public function getHloubka(): int ;

    public function getAdresa(): int ;

    public function setHloubka(int $hloubka) : void ;

    public function setAdresa(int $adresa) : void ;

}
