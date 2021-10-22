<?php

namespace Model\Testovaci\Repository;

use Model\Testovaci\Entity\TestovaciAssociatedCarrotEntityInterface;

/**
 *
 * @author vlse2610
 */
interface TestovaciCarrotRepositoryInterface {
    
    public function add ( TestovaciAssociatedCarrotEntityInterface $entity ) : void ;
  
    public function get ( TestovaciAssociatedCarrotEntityInterface $identity ) : ?TestovaciAssociatedCarrotEntityInterface ;       
    
    public function getByReference (TestovaciAssociatedCarrotEntityInterface $identityReference ) : ?TestovaciAssociatedCarrotEntityInterface ;    
        
    public function remove ( TestovaciAssociatedCarrotEntityInterface $entity ) : void  ;
    
    
    
    
}
