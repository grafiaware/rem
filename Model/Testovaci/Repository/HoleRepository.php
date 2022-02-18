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
    
    public function __construct( /*AccessorHydratorInterface $accessorHydratorEntity,
                                 AccessorHydratorInterface $accessorHydratorIdentity,*/
            
                                array $entityHydrators,                                       
                                array $identitiesHydrators,
            
                                IdentityMapInterface $identityMap,
                                RowObjectManagerInterface $rowObjectManager
              ) {
            
//            $this->registerHydratorEntity( $accessorHydratorEntity ); 
//            $this->registerHydratorIdentity( $accessorHydratorIdentity ); 
        
            $this->entityHydrators = $entityHydrators;         
            $this->identitiesHydrators = $identitiesHydrators;
            
            $this->identityMap = $identityMap;            
            $this->rowObjectManager = $rowObjectManager;            
    }          
    
    
    public function add ( HoleEntityInterface $entity ) : void {}
  
 
    public function getByHole ( HoleIdentityInterface $identity  ) : ?HoleEntityInterface {
        $re = $this->getEntity(  $identity, HoleIdentityInterface::class  );  
        return $re;
    }      
    
    public function getByReferenceRabbit ( RabbitIdentityInterface $parentIdentity  ) : ?HoleEntityInterface {
        $re = $this->getEntity(  $parentIdentity, RabbitIdentityInterface::class );  
        return   $re;
    }   
    
        
    public function remove ( HoleIdentityInterface $identity ) : void  {}
    
}