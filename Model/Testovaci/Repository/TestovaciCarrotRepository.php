<?php

namespace Model\Testovaci\Repository;

use Model\Testovaci\Repository\TestovaciCarrotRepositoryInterface;
use Model\Testovaci\Entity\TestovaciAssociatedCarrotEntityInterface;
use Model\Testovaci\Identity\TestovaciCarrotIdentityInterface;

use Model\Repository\RepositoryAbstract;




/**
 * Description of TestovaciCarrotRepository 
 * Je to CHILD REPOSITORY
 *
 * @author vlse2610
 */
class TestovaciCarrotRepository extends RepositoryAbstract implements TestovaciCarrotRepositoryInterface {
    
    
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
    
    
    
    

    public function add ( TestovaciAssociatedCarrotEntityInterface $entity ) : void {
    
    } 
  
    /*
     *  vola se  "lesa"
     * 
     */    
    public function get ( /*array $childIdentityHash */  TestovaciCarrotIdentityInterface $identity ) : ?TestovaciAssociatedCarrotEntityInterface {
    
    }      
//    /**
//     * Vrací jednu nebo žádnou entitu
//     * @param array $parentIdentityHash
//     * @return TestovaciAssociatedCarrotEntityInterface|null
//     */
//    public function getByReference ( $parentIdentityHash /*TestovaciAssociatedCarrotEntityInterface $identityReference*/ ) : ?TestovaciAssociatedCarrotEntityInterface {
//    
//    } 
    
    /**
     * VOLA se y repositorz
     * @param array $parentIdentityHash
     * @return iterable
     */
    public function findByReferenceKralik(    $parentIdentity ): ?\Traversable {
        
    }

    
        
    public function remove ( TestovaciCarrotIdentityInterface $identity  ) : void  {
    
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
