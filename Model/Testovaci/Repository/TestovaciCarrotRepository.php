<?php

namespace Model\Testovaci\Repository;

use Model\Testovaci\Repository\TestovaciCarrotRepositoryInterface;
use Model\Testovaci\Entity\TestovaciAssociatedCarrotEntityInterface;

use Model\Repository\RepositoryAbstract;




/**
 * Description of TestovaciCarrotRepository 
 * Je to CHILD REPOSITORY
 *
 * @author vlse2610
 */
class TestovaciCarrotRepository extends RepositoryAbstract implements TestovaciCarrotRepositoryInterface {

    public function add ( TestovaciAssociatedCarrotEntityInterface $entity ) : void {
    
    } 
  
    /*
     *  vola se  "lesa"
     * 
     */    
    public function get ( array $childIdentityHash  /* TestovaciAssociatedCarrotEntityInterface $identity*/ ) : ?TestovaciAssociatedCarrotEntityInterface {
    
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
    public function findByReference(   array $parentIdentity ): iterable {
        
    }

    
        
    public function remove ( TestovaciAssociatedCarrotEntityInterface $entity ) : void  {
    
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
