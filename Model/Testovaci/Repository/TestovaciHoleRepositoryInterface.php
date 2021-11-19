<?php
namespace Model\Testovaci\Repository;

use Model\Repository\RepositoryInterface;
use \Model\Testovaci\Entity\TestovaciAssociatedHoleEntityInterface;
use Model\Testovaci\Identity\TestovaciHoleIdentityInterface;



/**
 *
 * @author vlse2610
 */
interface TestovaciHoleRepositoryInterface  extends RepositoryInterface {
   
    public function add ( TestovaciAssociatedHoleEntityInterface $entity ) : void ;
  
    public function get ( /*array $childIdentityHash*/ TestovaciHoleIdentityInterface  $identity ) : ?TestovaciAssociatedHoleEntityInterface ;       
    
    public function getByReferenceKralik (  $parentIdentity  ) : ?\Traversable ;    
        
    public function remove ( TestovaciHoleIdentityInterface $identity ) : void  ;
    
    
}
