<?php
namespace Model\Hydrator\Exception;

use Model\Hydrator\Exception\RowObjectHydratorExceptionInterface;

/**
 * Description of UnexpectedDatetimeColumnValue
 *
 * @author vlse2610
 */
class DatetimeConversionFailureException  extends \UnexpectedValueException implements AttributeHydratorExceptionInterface {
    //put your code here
}
