<?php
namespace Model\Testovaci\Entity\Enum;

use Model\Entity\Enum\IdentityTypeEnum;
use Model\Testovaci\Identity\RabbitIdentityInterface;
use Model\Testovaci\Identity\KlicIdentityInterface;

/**
 * Description of RabbitIdentityNamesEnum
 *
 * @author vlse2610
 */
class RabbitIdentityNamesEnum extends IdentityTypeEnum {    
    const RABBITIDENTITYINTERFACE = RabbitIdentityInterface::class;
    const KLICIDENTITYINTERFACE = KlicIdentityInterface::class;
}