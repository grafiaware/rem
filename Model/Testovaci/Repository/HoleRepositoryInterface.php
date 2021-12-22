<?php
namespace Model\Testovaci\Repository;

use Model\Repository\RepositoryInterface;

use \Model\Testovaci\Entity\HoleEntityInterface;
use Model\Testovaci\Identity\HoleIdentityInterface;
use Model\Testovaci\Identity\RabbitIdentityInterface;




/**
 *
 * @author vlse2610
 */
interface HoleRepositoryInterface  extends RepositoryInterface {
   
    public function add ( HoleEntityInterface $entity ) : void ;
  
    public function getByHole ( HoleIdentityInterface  $identity ) : ?HoleEntityInterface ;       
    
    public function getByReferenceRabbit ( RabbitIdentityInterface $parentIdentity  ) : ?HoleEntityInterface ;    
        
    public function remove ( HoleIdentityInterface $identity ) : void  ;
    
    
}
