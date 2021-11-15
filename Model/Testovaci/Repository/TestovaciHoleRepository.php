<?php
namespace Model\Testovaci\Repository;

use Model\Testovaci\Repository\TestovaciHoleRepositoryInterface;
use Model\Testovaci\Entity\TestovaciAssociatedHoleEntityInterface;
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
    
    
    public function add ( TestovaciAssociatedHoleEntityInterface $entity ) : void {}
  
    public function get ( array $childIdentityHash  ) : ?TestovaciAssociatedHoleEntityInterface {}      
    
    public function getByReference (  $parentIdentity  ) : ?TestovaciAssociatedHoleEntityInterface {}   
        
    public function remove ( TestovaciAssociatedHoleEntityInterface $entity ) : void  {}
    
}