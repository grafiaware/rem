<?php

namespace Model\VS\Repository;

use Model\VS\Entity\TestovaciEntityInterface;
use Model\VS\Identity\TestovaciIdentityInterface;

use Model\Repository\RepositoryInterface;

/**
 *
 * @author vlse2610
 */
interface TestovaciRepositoryInterface extends RepositoryInterface {
     
    public function add ( TestovaciEntityInterface $entity ) : void ;
    
    public function get ( TestovaciIdentityInterface $identity ) : TestovaciEntityInterface ;
    
    public function remove ( TestovaciEntityInterface $entity ) : void  ;
    
    
    
    //public function createEntity() : TestovaciEntityInterface;
          
  
   
            
}
