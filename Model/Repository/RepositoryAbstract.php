<?php

namespace Model\Repository;

use Model\Dao\DaoInterface;
use Model\Dao\DaoKeyDbVerifiedInterface;
use Model\Dao\Exception\DaoKeyVerificationFailedException;

use Model\Hydrator\HydratorInterface;
use Model\Hydrator\AttributeAccessHydratorInterface;
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

use Model\Repository\Association\AssociationInterface;        
use Model\Repository\Association\AssociationOneToMany;
use Model\Repository\Association\AssociationOneToManyInterface;
use Model\Repository\Association\AssociationOneToOne;
use Model\Repository\Association\AssociationOneToOneInterface;


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
use Model\Testovaci\Repository\CarrotRepositoryInterface;

use Model\IdentityMap\IdentityMap;
use Model\IdentityMap\IndexMaker\IndexMaker;




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
    protected $associations = [];

    protected $entityHydrators = [];
    
    protected $identitiesHydrators = [];
   
        
    /**
     *
     * @var RowObjectManagerInterface
     * 
     */
    protected $rowObjectManager; 
    
    /**
     *
     * @var IdentityMap
     */
    protected $identityMap;
   
    
//    
//    /**
//     *
//     * @var IndexMakerInterface
//     */
//    private $indexMaker;
   
    
   

        

    /**
     *
     * @param string $entityInterfaceName Jm??no interface asociovan?? entity
     * @param array $parentReferenceKeyAttribute Atribut kl????e, kter?? je referenc?? na data rodi??e v ??lo??i??ti dat. V datab??zi jde o referen??n?? ciz?? kl????.
     * @param CarrotRepositoryInterface $repo
     */
    protected function registerOneToOneAssociation(  string      $entityInterfaceName,
                 /* $parentReferenceKeyAttribute */  RepoAssotiatedOneInterface $repo) {
        $this->associations[$entityInterfaceName] = new AssociationOneToOne( /*$parentReferenceKeyAttribute,*/ $repo);
    }

    /**
     *
     * @param string $entityInterfaceName Interface asociovan?? entity
     * @param array $parentReferenceKeyAttribute Atribut kl????e, kter?? je referenc?? na data rodi??e v ??lo??i??ti dat. V datab??zi jde o referen??n?? ciz?? kl????.
     * @param \Model\Repository\RepoAssotiatedOneInterface $repo
     */
    protected function registerOneToManyAssociation( string       $entityInterfaceName, 
                   /*$parentReferenceKeyAttribute*/  RepoAssotiatedManyInterface $repo) {  
        
        $this->associations[$entityInterfaceName] = new AssociationOneToMany( $repo ) ;                
              // ...identita... = $parentReferenceKeyAttribute, $repo);
    }

    
//    REGISTR bude objekt
//    protected function registerHydratorEntity( /*AccessorHydratorInterface*/ array $hydrators) {
//        $this->hydratorsEntity[] = $hydrators;
//    }
//    protected function registerHydratorIdentity( $identityInterfaceName, /*AccessorHydratorInterface*/ array $hydrators) {
//        $this->hydratorsIdentity[ $identityInterfaceName ] = $hydrators;
//    }

    protected function hydrateEntity( EntityInterface $entity, RowObjectInterface $rowObject ) {
        foreach ($this->entityHydrators as $hydrator) {
            $hydrator->hydrate( $entity, $rowObject);
        }
    }
    protected function hydrateIdentity( IdentityInterface $identity, string $identityInterfaceName, KeyInterface $key ) {
                //dvourozmerne pole s typem identity v 1.klici
        foreach ($this->identitiesHydrators as $identityTyp => $hydrators) {
            if ($identityTyp = $identityInterfaceName){
                foreach ($hydrators as $hydrator) {
                    $hydrator->hydrate( $identity, $key);                                    
                }
            }                       
        }      
    }

    protected function extractEntity( EntityInterface $entity, RowObjectInterface $rowObject ) {
        foreach ($this->entityHydrators as $hydrator) {
            $hydrator->extract($entity, $rowObject);
        }
    }
    
    protected function extractIdentity (IdentityInterface $identity, string $identityInterfaceName, KeyInterface $key ) {
        //dvourozmerne pole s typem identity v 1.klici
            foreach ($this->identitiesHydrators as $identityTyp => $hydrators) {
                if ($identityTyp = $identityInterfaceName){
                    foreach ($hydrators as $hydrator) {
                        $hydrator->extract($identity, $key);
                    }
                }
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
     * P??id?? dosud nepridanou  entitu do repository->collection.
     * Jedna se o jiz persistovane a pouzite entity.    
     *     
     * @param IdentityInterface $identity
     * @param string $identityInterfaceName
     * @return void
     * @throws UnableRecreateEntityException
     */
    protected function recreateEntity( IdentityInterface $identity, string $identityInterfaceName  ):  void {        
        
        $key = $this->rowObjectManager->createKey();
        $this->extractIdentity( $identity, $identityInterfaceName, $key );  
        
        //vyzvednout rowObject z managera
        $rowObject = $this->rowObjectManager->get( $key );
        if ($rowObject) {                                  
            /** @var EntityInterface $entity */   //vyrobit prazdnou entity
            $entity = $this->createEntity();  // !!!!definov??na v konkr??tn?? t????d??!!!!! - adept na entity managera                                              
            //---------------------------------------------
            $this->hydrateIdentity($entity->getIdentity( $identityInterfaceName ), $identityInterfaceName, $rowObject->getKey(/*...*/)); //pro vic klicu
                        
            try {
                 $this->recreateAssociations( $entity->getIdentity( $identityInterfaceName ) ,  $rowObject /*parent*/ );  // assoc.entity da do rowObjectu
            } catch (UnableToCreateAssotiatedChildEntity $unex) {              
                throw new UnableRecreateEntityException("Nelze obnovit agregovanou (vno??enou) entitu v repository ". get_called_class()." s indexem $index.", 0, $unex);
            }
            
            $this->hydrateEntity($entity, $rowObject);                        
                                                          
            $this->identityMap->add( $entity ); //zaradi do vsech
                                    
            
            
            $entity->setPersisted();   
            
            $this->flushed = false;
        }       
    }

    
     
    /**
     * 
     * @param IdentityInterface $parentIdentity
     * @param RowObjectInterface $rowObject
     * @return void
     * @throws \LogicException
     */
    protected function recreateAssociations(  IdentityInterface $parentIdentity, RowObjectInterface $rowObject  ): void {
        /** @var   AssociationInterface   $association */         
        foreach ($this->associations as $interfaceName => $association) {           
          
            if (  $association instanceof AssociationOneToOneInterface ) {
                $rowObject->$interfaceName = $association->childRepo->getByReference( $parentIdentity); }                        
            else if (  $association instanceof AssociationOneToManyInterface ) {                
                $rowObject->$interfaceName =  $association->childRepo->findByReference( $parentIdentity); }                                                                                  
            else  {
                throw new \LogicException("Nezn??m?? typ asociace pro $interfaceName") ;
            }        
                                                         
            //            ->childRepo->getByReference( $identity ) ->findByReference()
            //            //vraci jednu ( pro AssociationOneToOne) nebo vice ( pro AssociationOneToMany ) entitu-mrkovouentitu =   podle rodicovske identity                      
        }           
    }
    
    
    private function reread($index, $row, $entity) {
        try {
            $this->recreateAssociations($row);
        } catch (UnableToCreateAssotiatedChildEntity $unex) {
            throw new UnableRecreateEntityException("Nelze obnovit agregovanou (vno??enou) entitu v repository ". get_called_class()." s indexem $index.", 0, $unex);
        }
        $this->hydrate($entity, $row);
        $entity->setPersisted();
        $this->collection[$index] = $entity;
        $this->flushed = false;
    }


    
    
    protected function getKey($row) {
        $keyAttribute = $this->getKeyAttribute();
        if (is_array($keyAttribute)) {
            foreach ($keyAttribute as $field) {
                if( ! array_key_exists($field, $row)) {
                    throw new UnableRecreateEntityException("Nelze vytvo??it kl???? entity. Atribut kl????e obsahuje pole $field a pole ????dku dat pro vytvo??en?? entity neobsahuje prvek s takov??m km??nem.");
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
            throw new OperationWithLockedEntityException("Nelze p??id??vat p??idanou nebo smazanou entitu.");
        }
        if ($this instanceof RepositoryReadOnlyInterface) {
            throw new UnableWriteToReadOnlyRepoException("Repo je typu ".RepositoryReadOnlyInterface::class.", Nelze p??id??vat entity.");
        }
        if ( $entity->isPersisted() ) {
            
            // $indexMaker kde ho vzit
            $this->identityMap->add(  $entity,  $indexMaker);
                      
            
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
//            $this->flushChildRepos();  //pokud je vno??en?? agregovan?? entita - mus?? se prov??st jej?? insert                                     
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
//                    throw new UnableAddEntityException('Entitu s nastavenou hodnotou kl????e nelze zapsat do datab??ze.', 0, $failedExc);
//                }
//            } else {
//                $this->new[] = $entity;
//                $entity->lock();               
//            }            
                
   
    
//    /**
//     *
//     * @param type $entity Agreg??tn?? entita.
//     */        
//    protected function addAssociated($row, EntityInterface $entity) {
//        foreach ($this->associations as $interfaceName => $association) {
//            foreach ($row[$interfaceName] as $assocEntity) {  // asociovan?? entita nemus?? existovat - agreg??t je i tak validn??
//                if (!$assocEntity->isPersisted()) {
//                    $association->addAssociatedEntity($assocEntity);
//                }
//            }
//        }
//    }
    
    /**
     * 
     * @param RowObjectInterface $rowObject
     * @param EntityInterface $assocEntity
     */
    protected function addAssociated( RowObjectInterface $rowObject/*$row*/ /* ?????EntityInterface $assocEntity ?? */ ) {
         
        
        foreach ($this->associations as $interfaceName => $association) {
            foreach ( /*$row[$interfaceName]*/ $rowObject->$interfaceName as $assocEntity) {  // asociovan?? entita nemus?? existovat - agreg??t je i tak validn??
                if (!$assocEntity->isPersisted()) {
                    $association->addAssociated($assocEntity, /*, ?????*/);
                }
            }
        }
    }

    
    protected function getEntity( IdentityInterface $identity , string $identityInterfaceName  ) :?EntityInterface {
        if  ( !$this->identityMap->has( $identity, $identityInterfaceName )   )  {
            $this->recreateEntity( $identity, $identityInterfaceName );
        }
        return $this->identityMap->get( $identity, $identityInterfaceName) ?? NULL;   
    }
    
        
    /**
     * 
     * @param EntityInterface $entity
     * @return void
     * @throws OperationWithLockedEntityException
     */
    protected function removeEntity(EntityInterface $entity): void {
        if ($entity->isLocked()) {
            throw new OperationWithLockedEntityException("Nelze mazat (pr??v??) p??idanou nebo smazanou entitu.");
        }
        if ($this instanceof RepositoryReadOnlyInterface) {
            throw new UnableWriteToReadOnlyRepoException("Repo je typu ".RepositoryReadOnlyInterface::class.", Nelze odeb??rat entity.");
        }        
        if ($entity->isPersisted()) {
            $this->removed[  ] = $entity;
            
            //$index = IndexMaker::IndexFromIdentity($entity->getIdentity()) ;
            
            if  ( $this->identityMap->has($entity) ) {
                    $this->identityMap->remove($entity);
            }
            

            //$entity->setUnpersisted();
            
            $entity->lock();
        } //else  bud notice do logu - mazu nepersistovamou entitu / nebo nic nedelat
        
        
        $this->flushed = false;
    }

    /**
     *
     * @param string $entityInterfaceName
     * @param type $entity Entita nebo null. Asociovan?? entita (vr??cen?? pomoc?? ciz??ho kl????e) nemus?? existovat.
     */   
    protected function removeAssociated(  $rowObject/*$row*/,  /*?????*/ EntityInterface $entity  /*?????*/ ) {
        foreach ($this->associations as $interfaceName => $association) {
            if (isset($row[$interfaceName]) AND $row[$interfaceName]->isPersisted()) {  // asociovan?? entita nemus?? existovat - agreg??t je i tak validn??
                $association->removeAssociated($row[$interfaceName]  /*,?????*/);
            }
        }
    }

    
    
    public function flush(): void {
        if ($this->flushed) {
            return;
        }
                           //if ( ! ($this->dao instanceof DaoKeyDbVerifiedInterface)) {   // DaoKeyDbVerifiedInterface mus?? ukl??dat (insert) v??dy ji?? p??i nastavov??n?? hodnoty prim??rn??ho kl????e
        foreach ($this->new as $entity) {         

            /** @var  RowObjectInterface  $rowObject*/
            $rowObject = $this->rowObjectManager->createRowObject();
            $this->extractEntity( $entity, $rowObject);   
            //vadi ze nevim jaka je to identita, vsechny asi ??????
            // $this->extractIdentity( $entity->getIdentity(), $rowObject->getKey() ); 

            $this->rowObjectManager->add($rowObject);                              

            $this->addAssociated( $rowObject/*, $entity*/ );     // pridavam mrkev        //pridavam asociovanou entitu do potomk.repository
            $this->flushChildRepos();  //pokud je vno??en?? agregovan?? entita - mus?? se prov??st jej?? insert

            $entity->setPersisted();       
            $entity->unLock();
        }

        $this->new = []; // p??i dal????m pokusu o find se bude volat recteateEntity, entita se zp??tn?? na??te z db (v??etn?? p????padn??ho autoincrement id a dal????ch generovan??ch sloupc??)



        foreach ($this->collection as $entity) {

            $key = $this->rowObjectManager->createKey();
             //vadi ze nevim jaka je to identita, vsechny asi ??????
            //   $this->extractIdentity( $entity->getIdentity(), $key ); 
            $rowObject = $this->rowObjectManager->get($key);
            $this->extractEntity($entity, $rowObject);       //obcerstveny rowObject

            $this->addAssociated($rowObject  /*, $entity*/ );
            $this->flushChildRepos();  //pokud je vno??en?? agregovan?? entita p??id??na pozd??ji - mus?? se prov??st jej?? insert te??

            if ($entity->isPersisted()) {
                if ($rowObject) {     // $row po extractu mus?? obsahovat n??jak?? data, kter?? je mo??no updatovat - v extractu mus?? b??t vynech??ny "readonly" sloupce
                        // $this->dao->update($row);         
                   //nedelat nic,...  vyse  se obcerstvil rowObject, ktery je v  rowObjectManageru
                }
            } else {
                throw new UnpersistedEntityInCollectionException ("V collection je nepersistovan?? entita.");
            }
        }
        $this->collection = [];


        foreach ($this->removed as $entity) {

            $key = $this->rowObjectManager->createKey();
             //vadi ze nevim jaka je to identita, vsechny asi ??????
            //  $this->extractIdentity( $entity->getIdentity(), $key ); 
            $rowObject = $this->rowObjectManager->get($key);
            //$this->extractEntity($entity, $rowObject);       //obcerstveny rowObject --- ma se delat???                                                

            $this->removeAssociated($rowObject /*, $entity*/ );
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


    
   protected function getIndexFromIdentityHash( array $identityHash ): string  {
        throw new BadImplementationOfChildRepository("Child repository must implement method getIndexFromIdentityHash().");
   }
   
   
     
    
}
