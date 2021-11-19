<?php

namespace Model\Testovaci\Repository;

use Model\Repository\RepositoryReadOnlyInterface;
use Model\Repository\RepositoryAbstract;

use Model\Hydrator\AccessorHydratorInterface;
use Model\RowObjectManager\RowObjectManagerInterface;
use Model\Testovaci\Entity\TestovaciEntityInterface;
use Model\Testovaci\Entity\TestovaciEntity;
use Model\Testovaci\Identity\TestovaciIdentityInterface;
use Model\Testovaci\Identity\TestovaciIdentity;


/**
 * Description of TestovaciRepositoryReadOnly
 *
 * @author vlse2610
 */
class TestovaciRepositoryReadOnly extends RepositoryAbstract implements  RepositoryReadOnlyInterface {

   
    
    function __construct( AccessorHydratorInterface $accessorHydratorEntity,
                          AccessorHydratorInterface $accessorHydratorIdentity,
                          RowObjectManagerInterface $rowObjectManager
            ) {
        
        $this->registerHydratorEntity( $accessorHydratorEntity ); 
        $this->registerHydratorIdentity( $accessorHydratorIdentity ); 
         
        $this->rowObjectManager = $rowObjectManager;
        
    }
    
    
    public function add( TestovaciEntityInterface $entity): void {
                
        $this->addEntity($entity);      
    }
    

    
    public function get( TestovaciIdentityInterface $identity):  ?TestovaciEntityInterface {
        //$index = $identity->getIndexFromIdentity();
        $index = IndexMaker::IndexFromIdentity($identity);                        
             
                // and ( !($identity->isLocked()) )  ) //and (!isset($this->new[$index] ))   
        
        if  ( $this->identityMap->has($index)   )  {
            $this->recreateEntity( $identity, $index );
        }
        
        return $this->identityMap->get($index) ?? NULL;           
    }
    
    
    

    public function remove( TestovaciEntityInterface $entity): void {
        
        $this->removeEntity($entity);    
    }

    
    
    /**
     * 
     * @return TestovaciEntityInterface
     */
    protected function createEntity() : TestovaciEntityInterface {        
        //vyrobit prazdnou konkr. entity
        return new TestovaciEntity ( new TestovaciIdentity() ) ;
        
    }
        
}
