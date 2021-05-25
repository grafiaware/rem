<?php
namespace Model\Repository;

use Model\Dao\DaoInterface;

use Model\Entity\Hydrator\EntityHydratorInterface;
use Model\Entity\EntityInterface;
use Model\Entity\Identity\IdentityInterface;

//use Model\RowObject\RowObject;
use Model\RowObject\RowObjectInterface;
use Model\RowObject\Hydrator\RowObjectHydratorInterface;

use Model\RowData\RowDataInterface;
use Model\RowData\RowData;

use Model\Repository\Association\AssociationOneToOneFactory;
use Model\Repository\Association\AssociationOneToManyFactory;
use Model\Repository\Exception\UnableToCreateAssotiatedChildEntity;
use Model\Repository\Exception\UnableRecreateEntityException;
/**
 * Description of RepoAbstract
 *
 *
 */
abstract class RepositoryAbstract implements RepositoryInterface {

    public static $counter;
    protected $count;
    protected $oid;

    protected $collection = [];  //persisted old
    protected $new = [];
    protected $removed = [];

    private $associations = [];

    private $entityHydrators = [];
    private $rowObjectHydrators = [];

    
    /**
     *
     * @var DaoInterface
     */
    protected $dao;
    

//    /**
//     * @var HydratorInterface array of
//     */
//    protected $hydrator;

    /**
     *
     * @param type $parentPropertyName
     * @param type $parentIdName
     * @param \Model\Repository\RepoAssotiatedOneInterface $repo
     */
    protected function registerOneToOneAssotiation($parentPropertyName, $parentIdName, RepoAssotiatedOneInterface $repo) {
        $this->associations[$parentPropertyName] = new AssociationOneToOneFactory($parentPropertyName, $parentIdName, $repo);
    }

    protected function registerOneToManyAssotiation($parentPropertyName, $parentIdName, RepoAssotiatedManyInterface $repo) {
        $this->associations[$parentPropertyName] = new AssociationOneToManyFactory($parentPropertyName, $parentIdName, $repo);
    }

    protected function addCreatedAssociations(&$row): void {
        foreach ($this->associations as $association) {
            $association->createAssociated($row);
        }
    }

    //---------------------------------------
    protected function registerEntityHydrator( EntityHydratorInterface $hydrator ) {
        $this->entityHydrators[] = $hydrator;
    }
    protected function registerRowObjectHydrator( RowObjectHydratorInterface $hydrator ) {
        $this->rowObjectHydrators[] = $hydrator;
    }
    //---------------------------------------
    
    
    
    protected function hydrateEntity(EntityInterface $entity, RowObjectInterface $rowObject) {                
        /** @var EntityHydratorInterface $hydrator */
        foreach ($this->entityHydrators as $hydrator) {
            $hydrator->hydrate( $entity, $rowObject);
        }
    }
    
    protected function hydrateRowObject(RowObjectInterface $rowObject, RowDataInterface $rowData) {
        /** @var RowObjectHydratorInterface $hydrator */
        foreach ($this->rowObjectHydrators as $hydrator) {
            $hydrator->hydrate( $rowObject, $rowData );
        }
    }

    protected function extract( EntityInterface $entity, RowDataInterface $rowData) {
        // rozdělit na extractEntity a extractRoeObject
        
//        $rowObject = $this->createRowObject();
        
        /** @var EntityHydratorInterface $hydrator */
        foreach ($this->entityHydrators as $hydrator) {
            $hydrator->extract($entity, $rowObject );
        }
        
        /** @var RowObjectHydratorInterface $hydrator */
        foreach ($this->rowObjectHydrators as $hydrator) {
            $hydrator->extract($rowObject, $rowData );
        }
           
    }
    
    

    /**
     *     
     */
    protected function recreateEntity( IdentityInterface $identity  /*, RowDataInterface $rowData   */ ): void {
//        if ($rowData) {
//            try {
//                $this->addCreatedAssociations($row);
//            } catch (UnableToCreateAssotiatedChildEntity $unex) {
//                throw new UnableRecreateEntityException("Nelze obnovit agregovanou entitu v repository ". get_called_class()." s indexem $index.", 0, $unex);
//            }
        
            /** @var RowObjectInterface $rowObject */
            $rowObject = $this->createRowObj();            
//    plneni        $this->hydrateRowObject($rowObject, $rowData);
            
            /** @var EntityInterface $entity */
            $entity = $this->createEntity( $identity );  // definována v konkrétní třídě - adept na entity managera
//    plneni        $this->hydrateEntity($entity, $rowObject);
//                                    
            $entity->setPersisted();
            $this->collection[$this->indexFromIdentity($identity)] = $entity;
        
    }
    
    

    protected function addEntity(EntityInterface $entity, $index=null): void {
        if ($index) {
            $this->collection[$index] = $entity;
        } else {
            $this->new[] = $entity;
        }
    }

    protected function removeEntity(EntityInterface $entity, $index=null): void {
        if ($index) {
            $this->removed[] = $entity;
            unset($this->collection[$index]);
        } else {   // smazání před uložením do db
            foreach ($this->new as $key => $new) {
                if ($new === $entity) {
                    unset($this->new[$key]);
                }
            }
        }
       
    }
    
    #### factory metoda - musí ae přestěhovat buď a) konstuktor repository dostanet factory objekt na výrobu RowData (nebo PdoRowData atp. 
    #### nebo b) dao musí mít public metodu (factory metodu), která bude výrábět RowData (tady bude muset být interface s návratovou hodnotou RowDataInterface)
    
    protected function createRowData(): RowDataInterface {
        return new RowData();
    }
    
    #### metody generující index ###################
    
    protected function indexFromIdentity( IdentityInterface $identity ) {
        $id = $identity->getKeyHash();
        return serialize( ksort ( $id , SORT_STRING ) );
        
//        $id = $identity->getKeyHash();
//        $i = ksort ( $id , SORT_STRING );
//        return serialize( $i );
        
       // return serialize( $identity->getKeyHash() );
    }
    
    #### flush & destruct ############################################
    public function flush(): void {
        return;   // zde je vypnutý flush
        if ( !($this instanceof RepoReadonlyInterface)) {
            /** @var \Model\Entity\EntityAbstract $entity */
            foreach ($this->collection as $entity) {
                $rowData = $this->createRowData();
                $this->extract($entity, $rowData);
                if ($entity->isPersisted()) {
                    $this->dao->update($rowData);
                } else {
                    throw new \LogicException("V collection je nepersistovaná entita.");
                }
            }
            foreach ($this->new as $entity) {
                $rowData = $this->createRowData();
                $this->extract($entity, $rowData);
                $this->dao->insert($rowData);
            }
            $this->new = []; // při dalším pokusu o find se bude volat recteateEntity, entita se zpětně načte z db (včetně případného autoincrement id a dalších generovaných sloupců)
            foreach ($this->removed as $entity) {
                $rowData = $this->createRowData();
                $this->extract($entity, $rowData);
                $this->dao->delete($rowData);
                $entity->setUnpersisted();
            }
            $this->removed = [];
        } else {
            if ($this->new OR $this->removed) {
                throw new \LogicException("Repo je read only a byly do něj přidány nebo z něj smazány entity.");
            }
        }
    }

    public function __destruct() {
        $this->flush();
    }


}
