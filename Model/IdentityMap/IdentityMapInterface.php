<?php
namespace Model\IdentityMap;

use Model\Entity\EntityInterface;
use Model\Entity\Identity\IdentityInterface;

/**
 * IdentityMap
 * 
 * @author vlse2610
 */
interface IdentityMapInterface {
              
    
    /**
     * Přidá  $entity do vsech 'index seznamu'. Vsech = pro kazdou identitu.
     * @param EntityInterface $entity
     * @return void
     */    
    public function add (  EntityInterface $entity  ) : void    ;
    
    
    
    /**
     * Podle $identity  vyzvedne entitu.
     * 
     * @param IdentityInterface $identity
     * @param string $identityInterfaceName
     * @return EntityInterface|null
     */
    public function get ( IdentityInterface $identity, string $identityInterfaceName ) : ?EntityInterface ;
    
    
    
    /**
     * 
     * @param EntityInterface $entity
     * @return void
     */
    public function remove (  EntityInterface $entity ) : void ;
    
    
    /**
     * Je v Mape entita?    
     * 
     * @param IdentityInterface $identity
     * @param string $identityInterfaceName
     * @return boolean
     */
    public function has (  IdentityInterface $identity, string $identityInterfaceName  ) : bool ;
    
    
    
    
    
}
