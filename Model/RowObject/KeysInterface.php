<?php
namespace Model\RowObject;

use Traversable;
use IteratorAggregate;

use Model\RowObject\Key\KeyInterface;


/**
 *
 * @author vlse2610
 */
interface KeysInterface extends Traversable, IteratorAggregate {

     public function append( KeyInterface $key ): void;
}
