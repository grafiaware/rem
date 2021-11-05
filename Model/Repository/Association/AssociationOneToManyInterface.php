<?php

namespace Model\Repository\Association;

use Model\Entity\EntityInterface;
use Model\Repository\Association\AssociationInterface;

/**
 *
 * 
 */
interface AssociationOneToManyInterface extends AssociationInterface {
    
    public function getAllAssociatedEntities(/*&$row*/): iterable;
    public function addAssociatedEntity(EntityInterface $entity = null);
    public function removeAssociatedEntity(EntityInterface $entity = null);

}
