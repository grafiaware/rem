<?php

namespace Model\Testovaci\Repository;

use Model\Testovaci\Entity\CarrotEntityInterface;
use Model\Testovaci\Identity\CarrotIdentityInterface;
use Model\Testovaci\Identity\RabbitIdentityInterface;
use Model\Repository\RepositoryInterface;


/**
 *
 * @author vlse2610
 */
interface CarrotRepositoryInterface   extends RepositoryInterface {
    
    public function add ( CarrotEntityInterface $entity ) : void ;
  
    public function getByCarrot (  CarrotIdentityInterface $identity ) : ?CarrotEntityInterface ;       
    
    public function findByReferenceRabbit( RabbitIdentityInterface $parentIdentity ): \Traversable;
        
    public function remove ( CarrotIdentityInterface $identity ) : void  ;
    
    
    
    
}
