<?php

namespace Model\Repository\Exception;

use Model\Repository\Exception\RepositoryExceptionInterface;
/**
 * Description of UnableRecreateEntityException
 *
 * @author pes2704
 */
class UnableToSetOnlyPublishedModeException extends \LogicException implements RepositoryExceptionInterface {

}
