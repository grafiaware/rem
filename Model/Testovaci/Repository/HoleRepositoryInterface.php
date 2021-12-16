<?php
namespace Model\Testovaci\Repository;

use Model\Repository\RepositoryInterface;
use \Model\Testovaci\Entity\HoleEntityInterface;
use Model\Testovaci\Identity\TestovaciHoleIdentityInterface;



/**
 *
 * @author vlse2610
 */
interface TestovaciHoleRepositoryInterface  extends RepositoryInterface {
   
    public function add ( HoleEntityInterface $entity ) : void ;
  
    public function get ( HoleIdentityInterface  $identity ) : ?HoleEntityInterface ;       
    
    public function getByReferenceKralik (  $parentIdentity  ) : ?HoleEntityInterface ;    
        
    public function remove ( TestovaciHoleIdentityInterface $identity ) : void  ;
    
    
}
