<?php

namespace Model\RowObject;

//...Row je 'prepravka na data' plain data object

/**
 *
 * @author vlse2610
 */
interface RowObjectInterface {
    public function getKeyHash(): array;
    public function setKeyHash(array $keyHash);
}
