<?php

namespace Model\VS\Repository;

use Model\VS\Entity\TestovaciEntityInterface;
use Model\VS\Entity\TestovaciEntity;
use Model\VS\Identity\TestovaciIdentityInterface;
use Model\VS\Identity\TestovacIdentity;


use Model\Repository\RepositoryAbstract;
use Model\Hydrator\AccessorHydratorInterface;
use Model\RowObjectManager\RowObjectManagerInterface;


/**
 * Description of TestovaciRepository
 *
 * @author vlse2610
 */
class TestovaciRepository extends RepositoryAbstract implements TestovaciRepositoryInterface  {
    
    
    function __construct( AccessorHydratorInterface $accessorHydrator,
                          RowObjectManagerInterface $rowObjectManager
            ) {
        
        $this->registerHydrator( $accessorHydrator ); 
        $this->rowObjectManager = $rowObjectManager;
        
    }
    
    
    public function add( TestovaciEntityInterface $entity): void {
                
        $this->addEntity($entity);      
    }
    

    
    public function get( TestovaciIdentityInterface $identity):  TestovaciEntityInterface {
        $index = $identity->getIndexFromIdentity();
        
        if (!isset($this->collection[$index]) /*and (!isset($this->removed[$index] )) */  ) {
                                    
            $this->recreateEntity(  /*$identity */ /*$index*/ ); // v abstractu,  
            // zarazeni do collection z uloziste( db, soubor , atd.... ), pod indexem  $index   
            // pozn. kdyz neni v ulozisti - asi neni ani $rowObject
        }
        
        return $this->collection[$index] ?? NULL;    
        
    }

    public function remove( TestovaciEntityInterface $entity): void {
        
        $this->removeEntity($entity);    
    }

    
    
    /**
     * 
     * @return TestovaciEntityInterface
     */
    public function createEntity() : TestovaciEntityInterface {        
        //vyrobit prazdnou konkr. entity
        return new TestovaciEntity ( new TestovacIdentity() ) ;
        
    }
        
    
            
    

}
