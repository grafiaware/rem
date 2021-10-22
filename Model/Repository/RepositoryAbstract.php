<?php

namespace Model\Repository;

use Model\Dao\DaoInterface;
use Model\Dao\DaoKeyDbVerifiedInterface;
use Model\Dao\Exception\DaoKeyVerificationFailedException;

use Model\Hydrator\HydratorInterface;
use Model\Hydrator\AttributeHydratorInterface;
use Model\Hydrator\AccessorHydratorInterface;

use Model\Entity\EntityInterface;
use Model\RowObject\RowObjectInterface;
use Model\RowObject\RowObject;
use Model\Entity\Identity\IdentityInterface;
use Model\RowObject\Key\KeyInterface;
use Model\RowObject\Key\Key;

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
use Model\Repository\Exception\OperationWithLockedEntityException;
use Model\Repository\Exception\UnpersistedEntityInCollectionException;
use Model\Repository\Exception\UnableWriteToReadOnlyRepoException;

use Model\Repository\RepositoryInterface;
use Model\Repository\RepositoryReadOnlyInterface;


/**
 * Description of RepoAbstract
 *
 * @author pes2704
 */
abstract class RepositoryAbstract implements RepositoryInterface {

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

    private $hydratorsEntity = [];
    private $hydratorsIdentity = [];
   
    
    
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

    
    
    protected function registerHydratorEntity( AccessorHydratorInterface $hydrator) {
        $this->hydratorsEntity[] = $hydrator;
    }
    protected function registerHydratorIdentity( AccessorHydratorInterface $hydrator) {
        $this->hydratorsIdentity[] = $hydrator;
    }

    protected function hydrateEntity( EntityInterface $entity, RowObjectInterface $rowObject ) {
        foreach ($this->hydratorsEntity as $hydrator) {
            $hydrator->hydrate( $entity, $rowObject);
        }
    }
    protected function hydrateIdentity( IdentityInterface $identity, KeyInterface $key ) {
        foreach ($this->hydratorsIdentity as $hydrator) {
            $hydrator->hydrate( $identity, $key);
        }
    }

    protected function extractEntity( EntityInterface $entity, RowObjectInterface $rowObject ) {
        foreach ($this->hydratorsEntity as $hydrator) {
            $hydrator->extract($entity, $rowObject);
        }
    }
    protected function extractIdentity ( IdentityInterface $identity, KeyInterface $key ) {
        foreach ($this->hydratorsIdentity as $hydrator) {
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
     * Přidá dosud nepridanou  entitu do repository->collection. Jedna se o jiz persistovane a pouzite entity.
     *      
     * @param type $identity
     * @return string|null  vlastne index
     * @throws UnableRecreateEntityException
     */
    protected function recreateEntity( IdentityInterface $identity  ):  ?string {        
        //$rowData = new RowData(); 
        //$rowData = $this->dao->get( $identity->getKeyHash() ); // vraci konstantni pole - hodnoty z úložistě, $keyHash  zatim neni v metode get pouzito      
   
        $key = $this->rowObjectManager->createKey();
        $this->extractIdentity( $identity, $key );

        
        //$this->rowObjectManager->createRowObject();
        $rowObject = $this->rowObjectManager->get( $key );
        //vyzvednout rowObject z managera
        if ($rowObject) {          
            
            //vyrobit prazdnou entity
            /** @var EntityInterface $entity */
            $entity = $this->createEntity();  // !!!!definována v konkrétní třídě!!!!! - adept na entity managera
          
            try {
                $this->recreateAssociations( $rowObject /*$row */);
            } catch (UnableToCreateAssotiatedChildEntity $unex) {
                throw new UnableRecreateEntityException("Nelze obnovit agregovanou (vnořenou) entitu v repository ". get_called_class()." s indexem $index.", 0, $unex);
            }
                                    
            //---------------------------------------------
            $this->hydrateEntity($entity, $rowObject);
            $this->hydrateIdentity($entity->getIdentity(), $rowObject->getKey());
                                           
            $index = $entity->getIdentity()->getIndexFromIdentity();          
            $this->collection[$index] = $entity;
            $entity->setPersisted();   
            
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
            $rowObject->$interfaceName/*$row[$interfaceName]*/ = $association->getAssociated( $rowObject(kralika)  /*$row*/);  //getAssociatedEntity(rodicovsky
        }     //mrkovaentita =   podle hodnoty idKralik_fk
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

    
    
    
    /**
     * 
     * @param EntityInterface $entity
     * @return void
     * @throws OperationWithLockedEntityException
     */
    protected function addEntity( EntityInterface $entity): void {
        if ($entity->isLocked()) {
            throw new OperationWithLockedEntityException("Nelze přidávat přidanou nebo smazanou entitu.");
        }
        if ($this instanceof RepositoryReadOnlyInterface) {
            throw new UnableWriteToReadOnlyRepoException("Repo je typu ".RepositoryReadOnlyInterface::class.", Nelze přidávat entity.");
        }
        if ($entity->isPersisted()) {
            $this->collection[ $entity->getIdentity()->getIndexFromIdentity() ] = $entity;            
            
        } else {                        
            $this->new[] = $entity;                
            $entity->lock();                              
        }
        $this->flushed = false;
    }
            
            
// NEDOROZUMENI ---     rowObjectManager->add($rowObject) ----jen pro "ulozit hned"
//            /** @var RowObjectInterface $rowObject */
//            $rowObject = $this->rowObjectManager->createRowObject();  
//            //persisted = false, locked = false
//            $this->extractEntity( $entity, $rowObject );
//            $this->extractIdentity( $entity->getIdentity(), $rowObject->getKey() );            
//            
//            $this->rowObjectManager->add($rowObject);       
//            
//            //-----------------------------------
//            if  ($rowObject->isChanged() ) {    //NEVIM JAK SE TO UDEJE           
//                $this->hydrateEntity($entity, $rowObject);
//                $rowObject->fetchChanged(); //na vymazani zmenenych
//            }                           
//            $this->addAssociated( $rowObject , $entity);     // pridavam mrkev        //pridavam asociovanou entitu do potomk.repository
//            $this->flushChildRepos();  //pokud je vnořená agregovaná entita - musí se provést její insert                                     
//            //--------------------------------------            
      
    
// NEDOROZUMENI ---     rowObjectManager->addRowObject) --------jen pro "ulozit hned"  RowObjectManagerSaveImmedientlyInterface           
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

    
    protected function getEntity(  IdentityInterface $identity ) :?EntityInterface {
        $index = $identity->getIndexFromIdentity();
                             
        if  ( !isset($this->collection[$index] )   )       /*and ( !($identity->isLocked()) ) */  //and (!isset($this->new[$index] ))                             
        {            
            /*$entity*/
            $index = $this->recreateEntity( $identity  ); // v abstractu,  
            // ZARADI DO COLLECTION z uloziste( db, soubor , atd.... ), pod indexem  $index   
            // pozn. kdyz neni v ulozisti - ...asi... neni ani $rowObject                        
        }
        
        return $this->collection[$index] ?? NULL;            
    }
    
    
    
    
    
    /**
     * 
     * @param EntityInterface $entity
     * @return void
     * @throws OperationWithLockedEntityException
     */
    protected function removeEntity(EntityInterface $entity): void {
        if ($entity->isLocked()) {
            throw new OperationWithLockedEntityException("Nelze mazat (právě) přidanou nebo smazanou entitu.");
        }
        if ($this instanceof RepositoryReadOnlyInterface) {
            throw new UnableWriteToReadOnlyRepoException("Repo je typu ".RepositoryReadOnlyInterface::class.", Nelze odebírat entity.");
        }        
        if ($entity->isPersisted()) {
            $this->removed[  ] = $entity;
            
            $index =  $entity->getIdentity()->getIndexFromIdentity();
            if (isset ($this->collection[$index]) ) {    ////  *** a ma tam byt :????? nekdy????
                unset($this->collection[$index]);
            }

            //$entity->setUnpersisted();
            
            $entity->lock();
        } //else  bud notice do logu - mazu nepersistovamou entitu / nebo nic nedelat
        
        
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
                           //if ( ! ($this->dao instanceof DaoKeyDbVerifiedInterface)) {   // DaoKeyDbVerifiedInterface musí ukládat (insert) vždy již při nastavování hodnoty primárního klíče
        foreach ($this->new as $entity) {         

            /** @var  RowObjectInterface  $rowObject*/
            $rowObject = $this->rowObjectManager->createRowObject();
            $this->extractEntity( $entity, $rowObject);   
            $this->extractIdentity( $entity->getIdentity(), $rowObject->getKey() ); 

            $this->rowObjectManager->add($rowObject);                              

            $this->addAssociated( $rowObject, $entity);     // pridavam mrkev        //pridavam asociovanou entitu do potomk.repository
            $this->flushChildRepos();  //pokud je vnořená agregovaná entita - musí se provést její insert

            $entity->setPersisted();       

            $entity->unLock();
        }

        $this->new = []; // při dalším pokusu o find se bude volat recteateEntity, entita se zpětně načte z db (včetně případného autoincrement id a dalších generovaných sloupců)



        foreach ($this->collection as $entity) {

            $key = $this->rowObjectManager->createKey();
            $this->extractIdentity( $entity->getIdentity(), $key ); 
            $rowObject = $this->rowObjectManager->get($key);
            $this->extractEntity($entity, $rowObject);       //obcerstveny rowObject

            $this->addAssociated($rowObject, $entity);
            $this->flushChildRepos();  //pokud je vnořená agregovaná entita přidána později - musí se provést její insert teď

            if ($entity->isPersisted()) {
                if ($rowObject) {     // $row po extractu musí obsahovat nějaká data, která je možno updatovat - v extractu musí být vynechány "readonly" sloupce
                        // $this->dao->update($row);         
                   //nedelat nic,...  vyse  se obcerstvil rowObject, ktery je v  rowObjectManageru
                }
            } else {
                throw new UnpersistedEntityInCollectionException ("V collection je nepersistovaná entita.");
            }
        }
        $this->collection = [];


        foreach ($this->removed as $entity) {

            $key = $this->rowObjectManager->createKey();
            $this->extractIdentity( $entity->getIdentity(), $key ); 
            $rowObject = $this->rowObjectManager->get($key);
            //$this->extractEntity($entity, $rowObject);       //obcerstveny rowObject --- ma se delat???                                                

            $this->removeAssociated($rowObject, $entity);                                                     
            $this->flushChildRepos();

            $this->rowObjectManager->remove($rowObject);   //$this->dao->delete($row);                

            $entity->setUnpersisted();
        }
        $this->removed = [];
        
        $this->rowObjectManager->flush();
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


    
    
    
    
    
    
     //--------------------------------------------------
    public function getCollectionProTest(): array  {
        return $this->collection;        
    }
    
    public function getNewProTest(): array  {
        return $this->new;        
    }
    public function getRemovedProTest(): array  {
        return $this->removed;        
    }
    
    
    
    
}
