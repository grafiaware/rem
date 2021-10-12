<?php

use Model\Repository\Exception\RepositoryExceptionInterface;


namespace Model\Repository\Exception;

/**
 * Description of UnableAddEntityException
 *
 * @author pes2704
 */
class UnableAddEntityException extends \UnexpectedValueException implements RepositoryExceptionInterface {
    //put your code here
}
