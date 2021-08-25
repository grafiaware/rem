<?php

namespace Model\Repository;

use Model\Dao\DaoInterface;
use Model\Dao\DaoKeyDbVerifiedInterface;
use Model\Dao\Exception\DaoKeyVerificationFailedException;

use Model\Hydrator\HydratorInterface;
use Model\Hydrator\AttributeHydratorInterface;

use Model\Entity\EntityInterface;
use Model\RowObject\RowObjectInterface;
use Model\Entity\Identity\IdentityInterface;
use Model\RowObject\Key\KeyInterface;

use Model\RowObjectManager\RowObjectManagerInterface;
//use Model\RowObjectManager\RowObjectManagerSaveImmedientlyInterface;
//use Model\RowObjectManager\Exception\RowObjectManagerSaveImmedientlyFailedException;

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
abstract class RepositoryAbstract {

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

    private $hydrators = [];
   
    
    
    /**
     *
     * @var RowObjectManagerInterface
     * 
     */
    protected $rowObjectManager; 
   
    

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

    
    
    protected function registerHydrator( AttributeHydratorInterface $hydrator) {
        $this->hydrators[] = $hydrator;
    }

    protected function hydrate( EntityInterface $entity,RowObjectInterface $rowObject ) {
        foreach ($this->hydrators as $hydrator) {
            $hydrator->hydrate($entity, $rowObject);
        }
    }

    protected function extract(EntityInterface $entity, RowObjectInterface $rowObject ) {
        foreach ($this->hydrators as $hydrator) {
            $hydrator->extract($entity, $rowObject);
        }
    }
     protected function extractI ( IdentityInterface $identity, KeyInterface $key ) {
        foreach ($this->hydrators as $hydrator) {
            $hydrator->extract($identity, $key);
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
     * Přidá 'novou = dosud nepridanou'  entitu do repository->collection. (jiz persistovane a pouzite entity)
     * 
     * @param type $index
     * @param RowObjectInterface $rowObject
     * @return string|null index
     * @throws UnableRecreateEntityException
     */
    protected function recreateEntity( $index,  RowObjectInterface $rowObject /*$row*/): ?string {
        if ($rowObject) {
            /** @var EntityInterface $entity */
            $entity = $this->createEntity();  // !!!!definována v konkrétní třídě!!!!! - adept na entity managera
          
            try {
                $this->recreateAssociations($rowObject /*$row */);
            } catch (UnableToCreateAssotiatedChildEntity $unex) {
                throw new UnableRecreateEntityException("Nelze obnovit agregovanou (vnořenou) entitu v repository ". get_called_class()." s indexem $index.", 0, $unex);
            }
            
            $this->hydrate($entity, $rowObject);
            $this->hydrate($entity->getIdentity, $rowObject->getKey());
            
            $entity->setPersisted();
            
            $this->collection[$index] = $entity;
            $this->flushed = false;
        }
        return $index ?? null;
    }

    private function reread($index, $row, $entity) {
        try {
            $this->recreateAssociations($row);
        } catch (UnableToCreateAssotiatedChildEntity $unex) {
            throw new UnableRecreateEntityException("Nelze obnovit agregovanou (vnořenou) entitu v repository ". get_called_class()." s indexem $index.", 0, $unex);
        }
        $this->hydrate($entity, $row);
        $entity->setPersisted();
        $this->collection[$index] = $entity;
        $this->flushed = false;
    }

    
    
    protected function recreateAssociations( $rowObject /*&$row*/): void {
        foreach ($this->associations as $interfaceName => $association) {
            $rowObject->$interfaceName/*$row[$interfaceName]*/ = $association->getAssociated( $rowObject  /*$row*/);
        }
    }

    protected function getKey($row) {
        $keyAttribute = $this->getKeyAttribute();
        if (is_array($keyAttribute)) {
            foreach ($keyAttribute as $field) {
                if( ! array_key_exists($field, $row)) {
                    throw new UnableRecreateEntityException("Nelze vytvořit klíč entity. Atribut klíče obsahuje pole $field a pole řádku dat pro vytvoření entity neobsahuje prvek s takovým kménem.");
                }
                $key[$field] = $row[$field];
            }
        } else {
            $key = $row[$keyAttribute];
        }
        return $key;
    }

//    protected function indexFromKey($key) {
//        if (is_array($key)) {
//            return implode(array_values($key));
//        } else{
//            return $key;
//        }
//    }

    protected function indexFromEntity(  EntityInterface $entity ) {
        
        foreach ( ksort (\get_object_vars($entity->getIdentity()) ) as $nameAttr=>$value ) {            
           $index .= $value;                        
        }
        return $index;           
    }
    
    
    
    /**
     * 
     * @param EntityInterface $entity
     * @return void
     * @throws OperationWithLockedEntityException
     * @throws UnableAddEntityException
     */
    protected function addEntity( EntityInterface $entity): void {
        if ($entity->isLocked()) {
            throw new OperationWithLockedEntityException("Nelze přidávat přidanou nebo smazanou entitu.");
        }
        if ($entity->isPersisted()) {
            $this->collection[$this->indexFromEntity($entity)] = $entity;
        } else {
            
            //$key = $this->rowObjectManager->createKey();
            $rowObject = $this->rowObjectManager->createRowObject(/*$key*/);
            $this->extract($entity, $rowObject);
            
            $this->rowObjectManager->addRowObject($rowObject);
            if  ($rowObject->isChanged() ) {                
                $this->hydrate($entity, $rowObject);
                $rowObject->fetchChanged(); //na vymazani zmenenzch
            }
                        
//            if ( $this->rowObjectManager instanceof RowObjectManagerSaveImmedientlyInterface ) { ///nebude na co se optat
//               // $row = [];
//                $key = $this->rowObjectManager->createKey();
//                $rowObject = $this->rowObjectManager->createRowObject($key);
//                $this->extract($entity, $rowObject);
//                $this->rowObjectManager->addRowObject($rowObject);//               
//                try {
//                    //$this->dao->insertWithKeyVerification($row);
//                    $this->rowObjectManager->saveImmendietly($rowObject);                    
//                    $entity->setPersisted();
//                    $this->collection[$this->indexFromEntity($entity)] = $entity;                    
//                } catch ( RowObjectManagerSaveImmedientlyFailedException $failedExc) {
//                    throw new UnableAddEntityException('Entitu s nastavenou hodnotou klíče nelze zapsat do databáze.', 0, $failedExc);
//                }
//            } else {
//                $this->new[] = $entity;
//                $entity->lock();               
//            }            
                
        }
        $this->flushed = false;
    }

    
    
    /**
     *
     * @param type $entity Agregátní entita.
     */
    protected function addAssociated( $rowObject/*$row*/, /*?????*/ EntityInterface $entity /*??????*/) {
        foreach ($this->associations as $interfaceName => $association) {
            foreach ( /*$row[$interfaceName]*/ $rowObject->$interfaceName as $assocEntity) {  // asociovaná entita nemusí existovat - agregát je i tak validní
                if (!$assocEntity->isPersisted()) {
                    $association->addAssociated($assocEntity, /*, ?????*/);
                }
            }
        }
    }

    protected function removeEntity(EntityInterface $entity): void {
        if ($entity->isLocked()) {
            throw new OperationWithLockedEntityException("Nelze mazat přidanou nebo smazanou entitu.");
        }
        if ($entity->isPersisted()) {
            $index = $this->indexFromEntity($entity);
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
    protected function removeAssociated(  $rowObject/*$row*/,  /*?????*/ EntityInterface $entity  /*?????*/ ) {
        foreach ($this->associations as $interfaceName => $association) {
            if (isset($row[$interfaceName]) AND $row[$interfaceName]->isPersisted()) {  // asociovaná entita nemusí existovat - agregát je i tak validní
                $association->removeAssociated($row[$interfaceName]  /*,?????*/);
            }
        }
    }

    public function flush(): void {
        if ($this->flushed) {
            return;
        }
        if ( !($this instanceof RepoReadonlyInterface)) {

            if ( ! ($this->dao instanceof DaoKeyDbVerifiedInterface)) {   // DaoKeyDbVerifiedInterface musí ukládat (insert) vždy již při nastavování hodnoty primárního klíče
                foreach ($this->new as $entity) {         
                    
                    $key = $this->rowObjectManager->createKey();
                    $rowObject = $this->rowObjectManager->createRowObject($key);

                    $this->extract($entity, $rowObject);
                                                                                                    
                    $this->rowObjectManager->addRowObject($rowObject);
                    
                    $this->addAssociated( $rowObject /*$row*/, $entity);     // pridavam mrkev        //pridavam asociovanou entitu do potomk.repositorz
                    $this->flushChildRepos();  //pokud je vnořená agregovaná entita - musí se provést její insert
                                       
                    $entity->setPersisted();                                        
                }
            }
            $this->new = []; // při dalším pokusu o find se bude volat recteateEntity, entita se zpětně načte z db (včetně případného autoincrement id a dalších generovaných sloupců)

            
            
            foreach ($this->collection as $entity) {
                
                $key = $this->rowObjectManager->createKey();
                $rowObject = $this->rowObjectManager->createRowObject($key);
                
                $this->extract($entity, $rowObject);   
                               
                $this->addAssociated($rowObject, $entity);
                $this->flushChildRepos();  //pokud je vnořená agregovaná entita přidána později - musí se provést její insert teď
                
                if ($entity->isPersisted()) {
                    if ($rowObject) {     // $row po extractu musí obsahovat nějaká data, která je možno updatovat - v extractu musí být vynechány "readonly" sloupce
                       // $this->dao->update($row);
                        $this->rowObjectManager->flush();
                    }
                } else {
                    throw new \LogicException("V collection je nepersistovaná entita.");
                }
            }
            $this->collection = [];
            

            foreach ($this->removed as $entity) {
                $key = $this->rowObjectManager->createKey();
                $rowObject = $this->rowObjectManager->createRowObject($key);              
                
                $this->extract($entity, $rowObject);
                
                $this->removeAssociated($rowObject, $entity);                                                     
                $this->flushChildRepos();
                
                $this->rowObjectManager->removeRowObject($rowObject);   //$this->dao->delete($row);                
                
                $entity->setUnpersisted();
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
