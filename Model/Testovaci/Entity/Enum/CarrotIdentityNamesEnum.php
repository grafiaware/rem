<?php
namespace Model\Testovaci\Entity\Enum;

use Model\Entity\Enum\IdentityTypeEnum;
use Model\Testovaci\Identity\CarrotIdentityInterface;
use Model\Testovaci\Identity\RabbitIdentityInterface;

/**
 * Description of CarrotIdentityNamesEnum
 *
 * @author vlse2610
 */
class CarrotIdentityNamesEnum extends IdentityTypeEnum {
     const CARROTIDENTITYINTERFACE = CarrotIdentityInterface::class;
     const RABBITIDENTITYINTERFACE = RabbitIdentityInterface::class;
}
