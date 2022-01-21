<?php

namespace Model\Testovaci\Repository;

use Model\Testovaci\Repository\CarrotRepositoryInterface;
use Model\Testovaci\Entity\CarrotEntityInterface;
use Model\Testovaci\Identity\CarrotIdentityInterface;
use Model\Testovaci\Identity\RabbitIdentityInterface;
use Model\IdentityMap\IdentityMapInterface;


use Model\Hydrator\AccessorHydratorInterface;
use Model\Repository\RepositoryAbstract;
use Model\RowObjectManager\RowObjectManagerInterface;




/**
 * Description of CarrotRepository 
 * Je to CHILD REPOSITORY
 *
 * @author vlse2610
 */
class CarrotRepository extends RepositoryAbstract implements CarrotRepositoryInterface {
    
    
    function __construct(   //AccessorHydratorInterface $accessorHydratorEntity,
                            //AccessorHydratorInterface $accessorHydratorIdentity,
                            array $entityHydrators,                                       
                            array $identitiesHydrators,
            
                            IdentityMapInterface $identityMap,
                            RowObjectManagerInterface $rowObjectManager                                 
            
            
              ) {
            
//            $this->registerHydratorEntity( $accessorHydratorEntity ); 
//            $this->registerHydratorIdentity( $accessorHydratorIdentity ); 
            $this->entityHydrators = $entityHydrators;         
            $this->identitiesHydrators = $identitiesHydrators;

            $this->identityMap = $identityMap;
            
            $this->rowObjectManager = $rowObjectManager;            
    }                        
    
    
    
    

    public function add ( CarrotEntityInterface $entity ) : void {
    
    } 
      
    public function getByCarrot ( CarrotIdentityInterface $identity ) : ?CarrotEntityInterface {
        $re = $this->getEntity(  $identity, CarrotIdentityInterface::class  );  
        return   $re;
    
    }      
    
    
    
//    /**
//     * Vrací jednu nebo žádnou entitu
//     * @param array $parentIdentityHash
//     * @return TestovaciAssociatedCarrotEntityInterface|null
//     */
//    public function getByReference ( $parentIdentityHash /*TestovaciAssociatedCarrotEntityInterface $identityReference*/ ) : ?TestovaciAssociatedCarrotEntityInterface {
//    
//    } 
    
   
    public function findByReferenceRabbit(  RabbitIdentityInterface  $parentIdentity ): ?CarrotEntityInterface {
        $re = $this->getEntity(  $parentIdentity, RabbitIdentityInterface::class );  
        return   $re;
    }   
     
    public function remove ( CarrotIdentityInterface $identity  ) : void  {
    
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
