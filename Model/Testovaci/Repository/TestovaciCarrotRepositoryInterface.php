<?php

namespace Model\Testovaci\Repository;

use Model\Testovaci\Entity\TestovaciAssociatedCarrotEntityInterface;
use Model\Testovaci\Identity\TestovaciCarrotIdentityInterface;
use Model\Repository\RepositoryInterface;

/**
 *
 * @author vlse2610
 */
interface TestovaciCarrotRepositoryInterface   extends RepositoryInterface {
    
    public function add ( TestovaciAssociatedCarrotEntityInterface $entity ) : void ;
  
    public function get ( /*array $childIdentityHash */ TestovaciCarrotIdentityInterface $identity ) : ?TestovaciAssociatedCarrotEntityInterface ;       
    
    public function findByReferenceKralik(  $parentIdentity ): ?\Traversable;
        
    public function remove ( TestovaciCarrotIdentityInterface $identity ) : void  ;
    
    
    
    
}
