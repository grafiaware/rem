<?php
namespace Model\Entity\Identity;

use Model\Entity\AccessorInterface;

/**
 *
 * @author vlse2610
 */
interface IdentityInterface  extends AccessorInterface{
    
    public function getIndexFromIdentity() ;
}
