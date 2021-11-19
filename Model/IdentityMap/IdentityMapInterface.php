<?php
namespace Model\IdentityMap;

use Model\Entity\EntityInterface;
use Model\Entity\Identity\IdentityInterface;

/**
 *
 * @author vlse2610
 */
interface IdentityMapInterface {
    
    //   $identityMapIndex pro primarni identitu
    //   $identityMapIndex pro cizi identitu
    //...
    
    
    /**
     * 
     * @param EntityInterface $entity
     * @return void
     */
    public function add (  EntityInterface $entity  ) : void    ;
    
    public function get ( IdentityInterface $identity ) : ?EntityInterface ;
    
    public function remove (  EntityInterface $entity  ) : void ;
    
    public function has (  EntityInterface $entity  ) : boolean ;
    
    
    
    
    
}
