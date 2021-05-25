<?php

namespace Model\Entity;

/**
 *
 * @author vlse2610
 */
interface SvobodovaEntitaInterface extends EntityInterface {
    public function getKuk();

    public function getBuk();

    public function setKuk($kuk);

    public function setBuk($buk);
}
