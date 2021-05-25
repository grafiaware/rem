<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Model\RowObject;

/**
 * Description of RowObjectAbstract
 *
 * @author vlse2610
 */
class RowObjectAbstract implements RowObjectInterface {
    
    private $keyHash;
    
    public function getKeyHash(): array {
        $this->keyHash;
    }
    
    public function setKeyHash(array $keyHash) {
        $this->keyHash = $keyHash;
    }
}
