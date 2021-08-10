<?php

namespace Model\VS\Repository;

use Model\VS\Entity\TestovaciEntityInterface;
use Model\VS\Identity\TestovaciIdentityInterface;

use Model\Repository\RepositoryAbstract;
use Model\Hydrator\AccessorHydratorInterface;

/**
 * Description of TestovaciRepository
 *
 * @author vlse2610
 */
class TestovaciRepository extends RepositoryAbstract implements TestovaciRepositoryInterface  {
    public $hydratorsE = [];
    
    function __construct( AccessorHydratorInterface $accessorHydrator
                         ) {
        
        $this->registerHydrator( $accessorHydrator ); 
        
    }
    
    public function add( TestovaciEntityInterface $entity): void {
                
        $this->addEntity($entity);
        
        
    }

    public function get( TestovaciIdentityInterface $identity):  TestovaciEntityInterface {
        
    }

    public function remove( TestovaciEntityInterface $entity): void {
        
    }

    
    

}
