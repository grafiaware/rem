<?php
namespace Model\Piskoviste\Enum;

use Model\Piskoviste\Enum\MojeEnumClass;
use Pes\Type\Exception\ValueNotInEnumException;

/**
 * Description of zkouskaEnumClass
 *
 * @author vlse2610
 */
class ZkouskaEnumClass {
    
    public function zavolejMe($param) {
        $mojeEnum = new MojeEnumClass();
        try {
            $hodnota = $mojeEnum($param);
        } catch (ValueNotInEnumException $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    
}