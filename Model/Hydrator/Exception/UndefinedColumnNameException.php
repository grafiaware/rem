<?php
namespace Model\Hydrator\Exception;

use Model\Hydrator\Exception\AttributeHydratorExceptionInterface;

/**
 * Description of UnexpectedPropertyNameException
 *
 * @author pes2704
 */
class UndefinedColumnNameException extends \UnexpectedValueException implements AttributeHydratorExceptionInterface {
    //put your code here
}
