<?php
namespace Model\Repository;

use Model\Dao\DaoInterface;
use Model\Dao\DaoKeyDbVerifiedInterface;
use Model\Dao\Exception\DaoKeyVerificationFailedException;

use Model\RowData\PdoRowData;



use Model\Hydrator\AccessorHydratorInterface; //#
use Model\Hydrator\AttributeHydratorInterface;
//use Model\Entity\EntityInterface; //#
use Model\Entity\AccessorInterface; //#
use Model\Entity\EntityAbstract;

use Model\RowObject\AttributeInterface; //#
use Model\RowObject\RowObject;//#
use Model\RowObject\RowObjectAbstract;//#
use Model\RowObject\Key\Key;//#


use Model\Repository\Association\AssociationOneToOne;     
use Model\Repository\Association\AssociationOneToMany;
use Model\Repository\RepoAssotiatedOneInterface;
use Model\Repository\RepoAssotiatedManyInterface;
use Model\Repository\Exception\UnableToCreateAssotiatedChildEntity;
use Model\Repository\Exception\UnableRecreateEntityException;
use Model\Repository\Exception\BadImplemntastionOfChildRepository;

use Model\Repository\Exception\UnableAddEntityException;
use Model\Repository\Exception\OperationWithLockedEntityException;

/**
 * Description of RepoAbstract
 *
 * @author pes2704
 */
abstract class RepoAbstract_vs {

    public static $counter;
    protected $count;
    protected $oid;

    protected $collection = [];
    protected $new = [];
    protected $removed = [];

    private $flushed = false;

    /**
     *
     * @var []
     */
    private $associations = [];

    //private $hydrators = [];
    
    private $hydratorsEntity = [];
    private $hydratorsObject = [];

    /**
     * @var DaoInterface | DaoKeyDbVerifiedInterface
     * @var  DaoKeyDbVerifiedInterface
     */
    protected $dao;

    /**
     * @var HydratorInterface array of
     */
    protected $hydrator;

    /**
     *
     * @param string $entityInterfaceName Jméno interface asociované entity
     * @param array $parentReferenceKeyAttribute Atribut klíče, který je referencí na data rodiče v úložišti dat. V databázi jde o referenční cizí klíč.
     * @param \Model\Repository\RepoAssotiatedOneInterface $repo
     */
    protected function registerOneToOneAssociation($entityInterfaceName, $parentReferenceKeyAttribute, RepoAssotiatedOneInterface $repo) {
        $this->associations[$entityInterfaceName] = new AssociationOneToOne($parentReferenceKeyAttribute, $repo);
    }

    /**
     *
     * @param string $entityInterfaceName Interface asociované entity
     * @param array $parentReferenceKeyAttribute Atribut klíče, který je referencí na data rodiče v úložišti dat. V databázi jde o referenční cizí klíč.
     * @param \Model\Repository\RepoAssotiatedOneInterface $repo
     */
    protected function registerOneToManyAssociation($entityInterfaceName, $parentReferenceKeyAttribute, RepoAssotiatedManyInterface $repo) {
        $this->associations[$entityInterfaceName] = new AssociationOneToMany($parentReferenceKeyAttribute, $repo);
    }

    protected function registerHydratorObject( /*HydratorInterface*/ AttributeHydratorInterface $hydrator) {
        $this->hydratorsObject[] = $hydrator;
    }
    
    protected function registerHydratorEntity( $hydrator ) {
            $this->hydratorsEntity [] = $hydrator;
        }    
    
    
    
//############################
    protected function hydrate( AccessorInterface $entity, $rO ,/*AttributeInterface $rowObject*/ $rowData ) {
        /** @var AttributeHydratorInterface $hydrator */
        foreach ($this->hydratorsObject as $hydrator) {
            
            $hydrator->hydrate( /*$rowObject*/ $rO  , $rowData );
        }
        /** @var AccessorHydratorInterface $hydrator */
        foreach ($this->hydratorsEntity as $hydrator) {
            $hydrator->hydrate( $entity, $rO  /* $rowObject*/ );
        }
    }
//############################
    protected function extract( AccessorInterface $entity, AttributeInterface $rowObject, $rowData) {
        /** @var AttributeHydratorInterface $hydrator */
        foreach ($this->hydratorsObject as $hydrator) {
            $hydrator->hydrate( $rowObject, $rowData );
        }
        /** @var AccessorHydratorInterface $hydrator */
        foreach ($this->hydratorsEntity as $hydrator) {
            $hydrator->extract( $entity, $rowObject);
        }
    }

    protected function createEntity() {
        throw new BadImplemntastionOfChildRepository("Child repository must implement method createEntity().");
    }

//    protected function indexFromKeyParams() {
//        throw new BadImplemntastionOfChildRepository("Child repository must implement method indexFromKeyParams().");
//    }
//
//    protected function indexFromEntity() {
//        throw new BadImplemntastionOfChildRepository("Child repository must implement method indexFromEntity().");
//    }
//
//    protected function indexFromRow() {
//        throw new BadImplemntastionOfChildRepository("Child repository must implement method indexFromRow().");
//    }

    
    /**
     * 
     * @param string $index
     * @param AttributeInterface $rowObject
     * @return string|null
     * @throws UnableRecreateEntityException
     */
    protected function recreateEntity( $index,   $rowData ): ?string {
   //protected function recreateEntity($index, AttributeInterface $rowObject): ?string {
        if ($rowData) {
            
            
            $entity = $this->createEntity();  // definována v konkrétní třídě - adept na entity managera
//            try {
//                $this->recreateAssociations($rowObject);
//            } catch (UnableToCreateAssotiatedChildEntity $unex) {
//                throw new UnableRecreateEntityException("Nelze obnovit agregovanou (vnořenou) entitu v repository ". get_called_class()." s indexem $index.", 0, $unex);
//            }
            $key = new Key( [] );
            $rO = $this->createRowObj( $key);
            
            $this->hydrate( $entity, $rO , $rowData);
            $entity->setPersisted();
            $this->collection[$index] = $entity;
            $this->flushed = false;
        }
        return $index ?? null;
    }

    private function reread($index, $rowObject, $entity) {
        try {
            $this->recreateAssociations($rowObject);
        } catch (UnableToCreateAssotiatedChildEntity $unex) {
            throw new UnableRecreateEntityException("Nelze obnovit agregovanou (vnořenou) entitu v repository ". get_called_class()." s indexem $index.", 0, $unex);
        }
        $this->hydrate($entity, $rowObject);
        $entity->setPersisted();
        $this->collection[$index] = $entity;
        $this->flushed = false;
    }

    protected function recreateAssociations($rowObject): void {
        foreach ($this->associations as $interfaceName => $association) {
            $row[$interfaceName] = $association->getAssociated($row);
        }
    }

//#################################??????????????????    
    protected function getKey(AttributeInterface $rowObject) {
//        $keyAttribute = $this->getKeyAttribute();
//        if (is_array($keyAttribute)) {
//            foreach ($keyAttribute as $field) {
//                if( ! array_key_exists($field, $row)) {
//                    throw new UnableRecreateEntityException("Nelze vytvořit klíč entity. Atribut klíče obsahuje pole $field a pole řádku dat pro vytvoření entity neobsahuje prvek s takovým kménem.");
//                }
//                $key[$field] = $row[$field];
//            }
//        } else {
//            $key = $row[$keyAttribute];
//        }
//        return $key;
        
        return $rowObject->key;
    }
//#################################??????????????????   
    protected function indexFromKey /*identity*/( AttributeInterface $key ) {
//        if (is_array($key)) {
//            return implode(array_values($key));
//        } else{
//            return $key;
//        }
        return $key->getIndexFromKeyRowObject;
    }
    protected function indexFromEntity( $entity ){

    }
    
    
    protected function addEntity( AccessorInterface $entity): void {
        if ($entity->isLocked()) {
            throw new OperationWithLockedEntityException("Nelze přidávat přidanou nebo smazanou entitu.");
        }
          /** @var EntityAbstract $entity */
        if ($entity->isPersisted()) {
  /*???*/ // indexFromEntity neni tady ,... v abstract
  //* ######??*/          // $entity->getIdentity()->getIndexFromIdentity;
            $this->collection[$this->indexFromEntity($entity)] = $entity; 
        } else {
   /*???*/  if ( $this->dao instanceof DaoKeyDbVerifiedInterface ) {
             
                    $key= new Key( [] );  // moje nesmyslne $generated
                    $rowObject = new RowObject($key) ;   
                    
                $this->extract($entity, $rowObject);
                try {
                    $this->dao->insertWithKeyVerification( $rowObject );
                    $entity->setPersisted();
                    $this->collection[$this->indexFromEntity($entity)] = $entity;
                } catch ( DaoKeyVerificationFailedException $verificationFailedExc) {
                    throw new UnableAddEntityException('Entitu s nastavenou hodnotou klíče nelze zapsat do databáze.', 0, $verificationFailedExc);
                }
            } else {
                $this->new[] = $entity;
                $entity->lock();
            }
        }
        $this->flushed = false;
    }

    /**
     *
     * @param type $entity Agregátní entita.
     */
    protected function addAssociated( AttributeInterface  /*rowObjhect*/$row, AccessorInterface $entity) {
        foreach ($this->associations as $interfaceName => $association) {
            foreach ($row[$interfaceName] as $assocEntity) {  // asociovaná entita nemusí existovat - agregát je i tak validní
                if (!$assocEntity->isPersisted()) {
                    $association->addAssociated($assocEntity);
                }
            }
        }
    }

    protected function removeEntity( AccessorInterface $entity): void {
        if ($entity->isLocked()) {
            throw new OperationWithLockedEntityException("Nelze mazat přidanou nebo smazanou entitu.");
        }
        if ($entity->isPersisted()) {
            $index = $this->indexFromEntity($entity); 
   /* #### ???*/         $entity->getIdentity()->getIndexFromIdentity;
            $this->removed[$index] = $entity;
            unset($this->collection[$index]);
            $entity->setUnpersisted();
            $entity->lock();
        } else {   // smazání před uložením do db
            foreach ($this->new as $key => $new) {
                if ($new === $entity) {
                    unset($this->new[$key]);
                }
            }
        }
        $this->flushed = false;
    }

    /**
     *
     * @param string $entityInterfaceName
     * @param type $entity Entita nebo null. Asociovaná entita (vrácená pomocí cizího klíče) nemusí existovat.
     */
    protected function removeAssociated(/*$rowObject???*/ $row, AccessorInterface $entity) {
        foreach ($this->associations as $interfaceName => $association) {
            if (isset($row[$interfaceName]) AND $row[$interfaceName]->isPersisted()) {  // asociovaná entita nemusí existovat - agregát je i tak validní
                $association->removeAssociated($row[$interfaceName]);
            }
        }
    }

    public function flush(): void {
        if ($this->flushed) {
            return;
        }
        if ( !($this instanceof RepoReadonlyInterface)) {
            /** @var EntityAbstract $entity */
            if ( ! ($this->dao instanceof DaoKeyDbVerifiedInterface)) {   // DaoKeyDbVerifiedInterface musí ukládat (insert) vždy již při nastavování hodnoty primárního klíče
                foreach ($this->new as $entity) {
                    // $row = [];
                    // !!!! vytvorit key
                    $key= new Key( [] );  // moje nesmzslne $generated
                    $rowObject = new RowObject($key) ;                     
                    $rowData = new \Model\RowData\RowData([]);
                    
                    $this->extract($entity, $rowObject, $rowData);
                    
                   //$this->dao->insert($row);
                   // tady vlozit  pres RowData
                    
            //                    $this->addAssociated($row, $entity);
            //                    $this->flushChildRepos();  //pokud je vnořená agregovaná entita - musí se provést její insert
            //                    $entity->setPersisted();
                    
                }
            }
            $this->new = []; // při dalším pokusu o find se bude volat recteateEntity, entita se zpětně načte z db (včetně případného autoincrement id a dalších generovaných sloupců)
//ATD,  ATD,  ATD
             /** @var AccessorInterface $entity */
            foreach ($this->collection as $entity) {
                  // $row = [];              
                 // !!!! vytvorit key
                    $key= new Key( [] );  // moje nesmzslne $generated
                    $rowObject = new RowObject($key) ;   
                    $rowData = new \Model\RowData\RowData([]);
                    
                $this->extract($entity, $rowObject, $rowData);
                
                
                
            //                $this->addAssociated($row, $entity);
            //                $this->flushChildRepos();  //pokud je vnořená agregovaná entita přidána později - musí se provést její insert teď
            //                if ($entity->isPersisted()) {
            //                    if ($row) {     // $row po extractu musí obsahovat nějaká data, která je možno updatovat - v extractu musí být vynechány "readonly" sloupce
            //                        $this->dao->update($row);
            //                    }
            //                } else {
            //                    throw new \LogicException("V collection je nepersistovaná entita.");
            //                }
            }
            $this->collection = [];

            foreach ($this->removed as $entity) {
               // $row = [];
                 // !!!! vytvorit key
                    $key= new Key( [] );  // moje nesmzslne $generated
                    $rowObject = new RowObject($key) ;   
                    
                $this->extract($entity, $rowObject, $rowData);
                
                
                
            //                $this->removeAssociated($row, $entity);
            //                $this->flushChildRepos();
            //                $this->dao->delete($row);
            //                $entity->setUnpersisted();
            }
            $this->removed = [];

        } else {
            if ($this->new OR $this->removed) {
                throw new \LogicException("Repo je read only a byly do něj přidány nebo z něj smazány entity.");
            }
        }
        $this->flushed = true;
    }

    private function flushChildRepos() {
        foreach ($this->associations as $association) {
            $association->flushChildRepo();
        }
    }

    public function __destruct() {
        $this->flush();
    }


}
