<?php

namespace Model\Testovaci\Repository;

use Model\Testovaci\Entity\RabbitEntityInterface;
use Model\Testovaci\Entity\RabbitEntity;
use Model\Testovaci\Entity\CarrotEntityInterface;
use Model\Testovaci\Entity\HoleEntityInterface;

use Model\Testovaci\Identity\RabbitIdentityInterface;
use Model\Testovaci\Identity\RabbitIdentity;
use Model\Testovaci\Identity\KlicIdentityInterface;
use Model\Testovaci\Identity\CarrotIdentityInterface;
use Model\Testovaci\Identity\HoleIdentityInterface;



use Model\Repository\RepositoryAbstract;
use Model\Hydrator\AccessorHydratorInterface;
use Model\RowObjectManager\RowObjectManagerInterface;
use Model\Repository\Exception\UnableRecreateEntityException;

use Model\IdentityMap\IdentityMapInterface;


/**
 * Description 
 *
 * @author vlse2610
 */
class RabbitRepository extends RepositoryAbstract implements RabbitRepositoryInterface  {
    
    /**
     * 
     * @param array $entityHydrators
     * @param array $identitiesHydrators
     * @param IdentityMapInterface $identityMap
     * @param RowObjectManagerInterface $rowObjectManager
     * @param \Model\Testovaci\Repository\CarrotRepositoryInterface $carrotRepository
     * @param \Model\Testovaci\Repository\HoleRepositoryInterface $holeRepository
     */
    function __construct( 
                          array $entityHydrators,              
//                          array $accessorHydratorRabbitIdentities,
//                          array $accessorHydratorKlicIdentities,  
                          array $identitiesHydrators,   //vicerozmerne [1.klic-podle typu identity] pole poli hydratoru
                                     
                          IdentityMapInterface $identityMap,   //IdentityMap .. je misto collection[],  v nem filtry identit
                          
                          RowObjectManagerInterface $rowObjectManager,                          
 
                          CarrotRepositoryInterface $carrotRepository = NULL,
                          HoleRepositoryInterface $holeRepository = NULL  
            
            //tovarna na  entity ??
            ) {
        
        //$this->registerHydratorEntity( $accessorHydratorEntity ); 
        
//        $this->registerHydratorIdentity( RabbitIdentityInterface::class, $accessorHydratorRabbitIdentities ); 
//        $this->registerHydratorIdentity( KlicIdentityInterface::class, $accessorHydratorKlicIdentities ); 
        
        $this->identitiesHydrators = $identitiesHydrators;
        $this->entityHydrators = $entityHydrators; 
        $this->rowObjectManager = $rowObjectManager;
        $this->identityMap = $identityMap;
        
        
        if ( $carrotRepository) {
            $this->registerOneToManyAssociation( CarrotEntityInterface::class,
                                               // /*$parentReferenceKeyAttribute*/ ["id1", "id2" ], //krali????
                                                $carrotRepository );
        }
        
        if ( $holeRepository) {
            $this->registerOneToOneAssociation( HoleEntityInterface::class,
                                               // /*$parentReferenceKeyAttribute*/ ["id1", "id2" ],
                                                $holeRepository );
        }
 
        
    }
    
    
    public function add( RabbitEntityInterface $entity ): void {                
        $this->addEntity($entity);      
    }
    

    
    public function getByRabbit( RabbitIdentityInterface $identity  ):  ?RabbitEntityInterface {
        $re = $this->getEntity(  $identity, RabbitIdentityInterface::class  );  
        return   $re;        
    }        
    
    public function getByKlic ( KlicIdentityInterface  $identity  ) : ?RabbitEntityInterface{
        $re = $this->getEntity(  $identity, KlicIdentityInterface::class  );  
        return   $re; 
    }
          
               
    
    
    
    public function getByReferenceCarrot( CarrotIdentityInterface $identity ) : ?RabbitEntityInterface {
        $re = $this->getEntity(  $identity, CarrotIdentityInterface::class );  
        return   $re; 
    }
    
    
    public function getByReferenceHole( HoleIdentityInterface $identity ) : ?RabbitEntityInterface {
        $re = $this->getEntity(  $identity, HoleIdentityInterface::class );  
        return   $re; 
    }
    
    
   
    
    
    
//        $index = $identity->getIndexFromIdentity();
//                             
//        if  ( !isset($this->collection[$index] )   )       /*and ( !($identity->isLocked()) ) */  //and (!isset($this->new[$index] ))                             
//        {            
//            /*$entity*/
//            $index = $this->recreateEntity( $identity  ); // v abstractu,  
//            // ZARADI DO COLLECTION z uloziste( db, soubor , atd.... ), pod indexem  $index   
//            // pozn. kdyz neni v ulozisti - ...asi... neni ani $rowObject                        
//        }
//        
//        return $this->collection[$index] ?? NULL;    


    public function remove( RabbitEntityInterface $entity): void {        
        $this->removeEntity($entity);    
    }

    
    
    /**
     * 
     * @return RabbitEntityInterface
     */
    protected function createEntity() : RabbitEntityInterface {        
        //vyrobit prazdnou konkr. entity
        return new RabbitEntity ( new RabbitIdentity() ) ;
        
    }
        
    
    protected function getIndexFromIdentityHash( array $identityHash ): string  {
        //$a = \get_object_vars($this); 
        $b = ksort ($identityHash);
        
        $index="";
        foreach (  $identityHash   as $nameAttr=>$value ) {            
           $index .= $value;                        
        }
        return $index;    
    } 
    
  

}
