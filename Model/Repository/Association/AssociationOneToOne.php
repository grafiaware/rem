<?php
namespace Model\Repository\Association;

use Model\Entity\EntityInterface;

use Model\Repository\RepositoryInterface;
use Model\Repository\Association\AssociationAbstract;
use Model\Repository\Association\AssociationOneToOneInterface;


use Model\Repository\Exception\UnableToCreateAssotiatedChildEntity;

/**
 * Description of AssotiatedRepo
 *
 */
class AssociationOneToOne extends AssociationAbstract implements AssociationOneToOneInterface {

    /**
     * @var RepoAssotiatedOneInterface
     */
    protected $childRepo;

    /**
     *
     * @param array $parentReferenceKeyAttribute Atribut klíče, který je referencí na data rodiče v úložišti dat. V databázi jde o referenční cizí klíč.
     * NEPOTREBUJEME
     * @param RepoAssotiatedOneInterface $childRepo Repo pro získání, ukládání a mazání asociovaných entit
     */
    public function __construct( /*$referenceKeyAttribute,*/ RepositoryInterface $childRepo) {
        //parent::__construct($referenceKeyAttribute);
        $this->childRepo = $childRepo;
    }

    
    public function getAssociatedEntity( /*&$row*/ ): ?EntityInterface{
         
    }
     
     
    
//    public function getAssociatedEntity( $identity /*rodicovsky*/ /*&$row*/): ?EntityInterface {
//        $childKey = $this->getChildKey($row);
//      $child = $this->childRepo->getByReference($childKey);
  
//        if (is_null($child)) {
//            $repoCls = get_class($this->childRepo);
//            throw new UnableToCreateAssotiatedChildEntity("Nelze vytvořit asociovanou entitu. Nebyla načtena entita z repository asociovaných entit $repoCls.");
//        }
                       
        
    //    $childE = $this->childRepo->getByReference( $identity ) ;           
    //    return $childE;
//    }

    
    public function addAssociatedEntity(EntityInterface $entity = null) {
        $this->childRepo->add($entity);
    }

    public function removeAssociatedEntity(EntityInterface $entity = null) {
        $this->childRepo->remove($entity);
    }
}
