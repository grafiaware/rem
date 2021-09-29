<?php

namespace Model\Testovaci\Repository;

use Model\Testovaci\Entity\TestovaciEntityInterface;
use Model\Testovaci\Entity\TestovaciEntity;
use Model\Testovaci\Identity\TestovaciIdentityInterface;
use Model\Testovaci\Identity\TestovaciIdentity;


use Model\Repository\RepositoryAbstract;
use Model\Hydrator\AccessorHydratorInterface;
use Model\RowObjectManager\RowObjectManagerInterface;
use Model\Repository\Exception\UnableRecreateEntityException;


/**
 * Description of TestovaciRepository
 *
 * @author vlse2610
 */
class TestovaciRepository extends RepositoryAbstract implements TestovaciRepositoryInterface  {
    
    
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
        $index = $identity->getIndexFromIdentity();
                        
        
        
        
        
        if  ( !isset($this->collection[$index] )   )
                /*and (!isset($this->removed[$index] )
                  and (!isset($this->new[$index] )) */   {
                                                
            /*$entity*/
            $index = $this->recreateEntity( $identity  /*$index*/ ); // v abstractu,  
            // ZARADI DO COLLECTION z uloziste( db, soubor , atd.... ), pod indexem  $index   
            // pozn. kdyz neni v ulozisti - ...asi... neni ani $rowObject
            
            
//             if (  !$indexVraceny ) {
//                  throw new UnableRecreateEntityException("Nelze obnovit entitu v repository ". get_called_class()." s indexem $index.");
//             }
            
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
    protected function createEntity() : TestovaciEntityInterface {        
        //vyrobit prazdnou konkr. entity
        return new TestovaciEntity ( new TestovaciIdentity() ) ;
        
    }
        
    
  

}
