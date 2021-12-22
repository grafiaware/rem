<?php

namespace Model\Testovaci\Repository;

use Model\Repository\RepositoryReadOnlyInterface;
use Model\Repository\RepositoryAbstract;

use Model\Hydrator\AccessorHydratorInterface;
use Model\RowObjectManager\RowObjectManagerInterface;
use Model\Testovaci\Entity\RabbitEntityInterface;
use Model\Testovaci\Entity\RabbitEntity;
use Model\Testovaci\Identity\RabbitIdentityInterface;
use Model\Testovaci\Identity\RabbitIdentity;


/**
 * Description of TestovaciRepositoryReadOnly
 *
 * @author vlse2610
 */
class RabbitRepositoryReadOnly extends RepositoryAbstract implements  RepositoryReadOnlyInterface {

   
    
    function __construct( AccessorHydratorInterface $accessorHydratorEntity,
                          AccessorHydratorInterface $accessorHydratorIdentity,
                          RowObjectManagerInterface $rowObjectManager
            ) {
        
        $this->registerHydratorEntity( $accessorHydratorEntity ); 
        $this->registerHydratorIdentity( $accessorHydratorIdentity ); 
         
        $this->rowObjectManager = $rowObjectManager;
        
    }
    
    
    public function add( RabbitEntityInterface $entity): void {
                
        $this->addEntity($entity);      
    }
    

    
    public function get( RabbitIdentityInterface $identity):  ?RabbitEntityInterface {
//        //$index = $identity->getIndexFromIdentity();
//        $index = IndexMaker::IndexFromIdentity($identity);                        
//             
//                // and ( !($identity->isLocked()) )  ) //and (!isset($this->new[$index] ))   
//        
//        if  ( $this->identityMap->has($index)   )  {
//            $this->recreateEntity( $identity, $index );
//        }
//        
//        return $this->identityMap->get($index) ?? NULL;           
    }
    
    
    
    
    
    

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
        
}
