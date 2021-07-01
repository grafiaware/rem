<?php
namespace Model\Hydrator\Exception;

use Model\Hydrator\Exception\RowObjectHydratorExceptionInterface;
/**
 * Description of UnexpectedPropertyNameException
 *
 * @author pes2704
 */
class UnknownPropertyNameException extends \UnexpectedValueException implements AttributeHydratorExceptionInterface {
    //put your code here
}
