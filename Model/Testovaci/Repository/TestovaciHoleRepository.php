<?php

namespace Model\Testovaci\Repository;


use Model\Testovaci\Repository\TestovaciHoleRepositoryInterface;
use Model\Testovaci\Entity\TestovaciAssociatedHoleEntityInterface;


/**
 * Description of TestovaciHoleRepository
 *
 * @author vlse2610
 */
class TestovaciHoleRepository extends RepositoryAbstract implements TestovaciHoleRepositoryInterface {
    
    
    public function add ( TestovaciAssociatedHoleEntityInterface $entity ) : void {}
  
    public function get ( array $childIdentityHash  ) : ?TestovaciAssociatedHoleEntityInterface {}      
    
    public function getByReference ( array $parentIdentity  ) : ?TestovaciAssociatedHoleEntityInterface {}   
        
    public function remove ( TestovaciAssociatedHoleEntityInterface $entity ) : void  {}
    
}