<?php
namespace Model\Entity\Identity\Hydrator;

use Model\Entity\Identity\IdentityInterface;
use Model\RowObject\RowObjectInterface;

use Model\Entity\Identity\Hydrator\Exception\MissingAttributeFieldValueException;
use Model\Entity\Identity\Hydrator\Exception\MissingPropertyRowObjectException;

/**
 *
 * @author pes2704
 */
interface IdentityHydratorInterface {
   
    /**
     * 
     * @param IdentityInterface $identity
     * @param RowObjectInterface $rowObject
     * @return void
     * @throws MissingPropertyRowObjectException
     */
    public function hydrate( IdentityInterface $identity, RowObjectInterface $rowObject): void;
     
    /**
     * 
     * @param IdentityInterface $identity
     * @param RowObjectInterface $rowObject
     * @return void
     * @throws MissingAttributeFieldValueException
     */
    public function extract(  IdentityInterface $identity, RowObjectInterface $rowObject): void;
    
}
