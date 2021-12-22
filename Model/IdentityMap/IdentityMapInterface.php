<?php
namespace Model\IdentityMap;

use Model\Entity\EntityInterface;
use Model\Entity\Identity\IdentityInterface;

/**
 * IdentityMapIndex pro jednu  identitu
 * @author vlse2610
 */
interface IdentityMapInterface {
              
    
    /**
     * Přidá  $entity do  index seznamu
     * @param EntityInterface $entity
     * @return void
     */    
    public function add (  EntityInterface $entity,  $index, string $identityInterfaceName ) : void    ;
    
    
    
    /**
     * Podle $identity  vyzvedne entitu ze seznamu.
     * @param \Model\IdentityMap\IdentityInterface $identity
     * @return EntityInterface|null
     */
    public function get ( IdentityInterface $identity, $identityInterfaceName ) : ?EntityInterface ;
    
    
    
    public function remove (  EntityInterface $entity , $identityInterfaceName ) : void ;
    
    
    /**
     * Je v Mape entita?
     * @param IdentityInterface $identity
     * @return boolean
     */
    public function has (  IdentityInterface $identity, $identityInterfaceName  ) : boolean ;
    
    
    
    
    
}
