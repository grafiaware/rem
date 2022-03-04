<?php
namespace Model\Entity;

use Model\Entity\Identity\IdentityInterface;

use Traversable;
use IteratorAggregate;

/**
 *
 * @author vlse2610
 */
interface IdentitiesInterface extends Traversable, IteratorAggregate {
   
    public function append( IdentityInterface $identity): void;
       
}
