<?php
namespace Model\Testovaci\Repository;

use Model\Testovaci\Repository\TestovaciHoleRepositoryInterface;
use Model\Testovaci\Entity\HoleEntityInterface;
use Model\Testovaci\Identity\TestovaciHoleIdentityInterface;

use Model\Repository\RepositoryAbstract;

/**
 * Description of TestovaciHoleRepository
 *
 * @author vlse2610
 */
class TestovaciHoleRepository extends RepositoryAbstract implements TestovaciHoleRepositoryInterface {
    
    public function __construct( AccessorHydratorInterface $accessorHydratorEntity,
                          AccessorHydratorInterface $accessorHydratorIdentity,
                          RowObjectManagerInterface $rowObjectManager
              ) {
            
            $this->registerHydratorEntity( $accessorHydratorEntity ); 
            $this->registerHydratorIdentity( $accessorHydratorIdentity ); 

            $this->rowObjectManager = $rowObjectManager;            
    }          
    
    
    public function add ( HoleEntityInterface $entity ) : void {}
  
    public function get ( HoleIdentityInterface $identity  ) : ?HoleEntityInterface {}      
    
    public function getByReferenceKralik (  $parentIdentity  ) : ?HoleEntityInterface {}   
        
    public function remove (TestovaciHoleIdentityInterface $identity ) : void  {}
    
}