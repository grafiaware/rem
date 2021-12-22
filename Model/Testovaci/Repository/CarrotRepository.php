<?php

namespace Model\Testovaci\Repository;

use Model\Testovaci\Repository\CarrotRepositoryInterface;
use Model\Testovaci\Entity\CarrotEntityInterface;
use Model\Testovaci\Identity\CarrotIdentityInterface;

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
    
    
    function __construct( AccessorHydratorInterface $accessorHydratorEntity,
                          AccessorHydratorInterface $accessorHydratorIdentity,
                          RowObjectManagerInterface $rowObjectManager
            
            //IdentityMap .. je misto collection[]
            //tovarna na  entity
            
            
              ) {
            
            $this->registerHydratorEntity( $accessorHydratorEntity ); 
            $this->registerHydratorIdentity( $accessorHydratorIdentity ); 

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
    
   
    public function findByReferenceRabbit(  RabbitIdentityInterface  $parentIdentity ): \Traversable {
        
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
