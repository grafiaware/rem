<?php

namespace Model\Testovaci\Repository;

use Model\Testovaci\Entity\TestovaciAssociatedCarrotEntityInterface;
use Model\Repository\RepositoryInterface;

/**
 *
 * @author vlse2610
 */
interface TestovaciCarrotRepositoryInterface   extends RepositoryInterface {
    
    public function add ( TestovaciAssociatedCarrotEntityInterface $entity ) : void ;
  
    public function get ( array $childIdentityHash /*TestovaciAssociatedCarrotEntityInterface $identity*/ ) : ?TestovaciAssociatedCarrotEntityInterface ;       
    
    public function findByReference(  $parentIdentity ): iterable;
        
    public function remove ( TestovaciAssociatedCarrotEntityInterface $entity ) : void  ;
    
    
    
    
}
