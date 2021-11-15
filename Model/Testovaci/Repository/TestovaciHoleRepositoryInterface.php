<?php
namespace Model\Testovaci\Repository;

use Model\Repository\RepositoryInterface;
use \Model\Testovaci\Entity\TestovaciAssociatedHoleEntityInterface;



/**
 *
 * @author vlse2610
 */
interface TestovaciHoleRepositoryInterface  extends RepositoryInterface {
   
    public function add ( TestovaciAssociatedHoleEntityInterface $entity ) : void ;
  
    public function get ( array $childIdentityHash  ) : ?TestovaciAssociatedHoleEntityInterface ;       
    
    public function getByReference (  $parentIdentity  ) : ?TestovaciAssociatedHoleEntityInterface ;    
        
    public function remove ( TestovaciAssociatedHoleEntityInterface $entity ) : void  ;
    
    
}
