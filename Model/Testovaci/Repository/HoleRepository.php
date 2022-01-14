<?php
namespace Model\Testovaci\Repository;

use Model\Testovaci\Repository\HoleRepositoryInterface;
use Model\Testovaci\Entity\HoleEntityInterface;
use Model\Testovaci\Identity\HoleIdentityInterface;
use Model\Testovaci\Identity\RabbitIdentityInterface;
use Model\Hydrator\AccessorHydratorInterface;
use Model\IdentityMap\IdentityMapInterface;

use Model\Repository\RepositoryAbstract;

/**
 * Description of TestovaciHoleRepository
 *
 * @author vlse2610
 */
class HoleRepository extends RepositoryAbstract implements HoleRepositoryInterface {
    
    public function __construct( AccessorHydratorInterface $accessorHydratorEntity,
                                 AccessorHydratorInterface $accessorHydratorIdentity,
                                 IdentityMapInterface $identityMap,
                                 RowObjectManagerInterface $rowObjectManager
              ) {
            
            $this->registerHydratorEntity( $accessorHydratorEntity ); 
            $this->registerHydratorIdentity( $accessorHydratorIdentity ); 

            $this->rowObjectManager = $rowObjectManager;            
    }          
    
    
    public function add ( HoleEntityInterface $entity ) : void {}
  
    public function getByHole ( HoleIdentityInterface $identity  ) : ?HoleEntityInterface {
        $re = $this->getEntity(  $identity, HoleIdentityInterface::class  );  
        return $re;
    }      
    
    public function getByReferenceRabbit ( RabbitIdentityInterface $parentIdentity  ) : ?HoleEntityInterface {}   
    
        
    public function remove ( HoleIdentityInterface $identity ) : void  {}
    
}