<?php
namespace Model\Repository\Association;

use Model\Entity\EntityInterface;

use Model\Repository\RepositoryInterface;
use Model\Repository\Association\AssociationAbstract;
use Model\Repository\Association\AssociationOneToManyInterface;

/**
 * Description of AssociationOneToManyFactory
 *
 * @author pes2704
 */
class AssociationOneToMany extends AssociationAbstract implements AssociationOneToManyInterface {

    /**
     * @var RepoAssotiatedManyInterface
     */
    protected $childRepo;

    /**
     *
     * @param type $parentReferenceKeyAttribute
     * @param RepoAssotiatedManyInterface $childRepo
     */
    public function __construct( /*$parentReferenceKeyAttribute,*/ RepositoryInterface $childRepo) {
       // parent::__construct($parentReferenceKeyAttribute);
        $this->childRepo = $childRepo;
    }

    public function getAllAssociatedEntities( /*&$row*/  ): iterable {
        
        
//        $childKey = $this->getChildKey($row);
//        $children = $this->childRepo->findByReference($childKey);
//        if (!$children) {
//            throw new UnableToCreateAssotiatedChildEntity("Nelze vytvořit asociované entity pro vlastnost rodiče '$this->parentPropertyName'. Nebyla načtena entita.");
//        }
//        return $children;
    }

    public function addAssociatedEntity(EntityInterface $entity = null) {
        $this->childRepo->add($entity);
    }

    public function removeAssociatedEntity(EntityInterface $entity = null) {
        $this->childRepo->remove($entity);
    }

}
