<?php

namespace Model\Repository\Exception;

use Model\Repository\Exception\RepositoryExceptionInterface;


/**
 * Description of OperationWithLockedEntityException
 *
 * @author pes2704
 */
class OperationWithLockedEntityException extends \LogicException implements RepositoryExceptionInterface {
    
}
