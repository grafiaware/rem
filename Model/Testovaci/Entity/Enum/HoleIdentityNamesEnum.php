<?php
namespace Model\Testovaci\Entity\Enum;

use Model\Entity\Enum\IdentityTypeEnum;
use Model\Testovaci\Identity\HoleIdentityInterface;
use Model\Testovaci\Identity\RabbitIdentityInterface;


/**
 * Description of HoleIdentityNamesEnum
 *
 * @author vlse2610
 */
class HoleIdentityNamesEnum  extends IdentityTypeEnum {
    const HOLEIDENTITYINTERFACE = HoleIdentityInterface::class;
    const RABBITIDENTITYINTERFACE = RabbitIdentityInterface::class; //fk

}
