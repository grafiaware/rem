<?php

namespace Model\Testovaci\Repository;

use Model\Testovaci\Repository\TestovaciCarrotRepositoryInterface;
use Model\Testovaci\Entity\TestovaciAssociatedCarrotEntityInterface;

use Model\Repository\RepositoryAbstract;




/**
 * Description of TestovaciCarrotRepository
 *
 * @author vlse2610
 */
class TestovaciCarrotRepository extends RepositoryAbstract implements TestovaciCarrotRepositoryInterface {

    public function add ( TestovaciAssociatedCarrotEntityInterface $entity ) : void {
    
    }
  
    public function get ( TestovaciAssociatedCarrotEntityInterface $identity ) : ?TestovaciAssociatedCarrotEntityInterface {
    
    }      
    
    public function getByReference (TestovaciAssociatedCarrotEntityInterface $identityReference ) : ?TestovaciAssociatedCarrotEntityInterface {
    
    }    
        
    public function remove ( TestovaciAssociatedCarrotEntityInterface $entity ) : void  {
    
    }
    
    
    
    
}
