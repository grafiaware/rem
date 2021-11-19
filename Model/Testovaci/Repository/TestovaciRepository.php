<?php

namespace Model\Testovaci\Repository;

use Model\Testovaci\Entity\TestovaciEntityInterface;
use Model\Testovaci\Entity\TestovaciEntity;
use Model\Testovaci\Identity\TestovaciIdentityInterface;
use Model\Testovaci\Identity\TestovaciIdentity;
use Model\Testovaci\Identity\TestovaciCarrotIdentityInterface;

use  Model\Testovaci\Repository\TestovaciHoleRepositoryInterface;
use Model\Testovaci\Repository\TestovaciCarrotRepositoryInterface;

use Model\Testovaci\Entity\TestovaciAssociatedCarrotEntityInterface;
use Model\Testovaci\Entity\TestovaciAssociatedHoleEntityInterface;


use Model\Repository\RepositoryAbstract;
use Model\Hydrator\AccessorHydratorInterface;
use Model\RowObjectManager\RowObjectManagerInterface;
use Model\Repository\Exception\UnableRecreateEntityException;

use Model\IdentityMap\IdentityMapInterface;


/**
 * Description of TestovaciRepository
 *
 * @author vlse2610
 */
class TestovaciRepository extends RepositoryAbstract implements TestovaciRepositoryInterface  {
    
    
    function __construct( AccessorHydratorInterface $accessorHydratorEntity,
                          AccessorHydratorInterface $accessorHydratorIdentity,
                          RowObjectManagerInterface $rowObjectManager,                          
            
                          IdentityMapInterface $identityMap,   //IdentityMap .. je misto collection[],                        
                          TestovaciCarrotRepositoryInterface $testovaciCarrotRepository = NULL,
                          TestovaciHoleRepositoryInterface $testovaciHoleRepository = NULL                                                  
            
            //tovarna na  entity
            ) {
        
        $this->registerHydratorEntity( $accessorHydratorEntity ); 
        $this->registerHydratorIdentity( $accessorHydratorIdentity ); 
         
        $this->rowObjectManager = $rowObjectManager;
        $this->identityMap = $identityMap;
        
        
        if ( $testovaciCarrotRepository) {
            $this->registerOneToManyAssociation( TestovaciAssociatedCarrotEntityInterface::class,
                                               // /*$parentReferenceKeyAttribute*/ ["id1", "id2" ], //kraličí
                                                $testovaciCarrotRepository );
        }
        
        if ( $testovaciHoleRepository) {
            $this->registerOneToOneAssociation( TestovaciAssociatedHoleEntityInterface::class,
                                               // /*$parentReferenceKeyAttribute*/ ["id1", "id2" ],
                                                $testovaciHoleRepository );
        }
        
        
    }
    
    
    public function add( TestovaciEntityInterface $entity): void {                
        $this->addEntity($entity);      
    }
    

    
    public function get( /*identityHash*/  TestovaciIdentityInterface $identity  ):  ?TestovaciEntityInterface {
        $re = $this->getEntity( /*$identityHash */   $identity );  
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
