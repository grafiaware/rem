<?php

namespace Model\Repository\Exception;

use Model\Repository\Exception\RepositoryExceptionInterface;

/**
 * Description of UnableWriteToReadOnlyRepoException
 *
 * @author vlse2610
 */
class UnableWriteToReadOnlyRepoException  extends \LogicException implements RepositoryExceptionInterface {
    //put your code here
}

