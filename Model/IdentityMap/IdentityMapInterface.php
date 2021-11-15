<?php
namespace Model\IdentityMap;

use Model\Entity\EntityInterface;
use Model\Entity\Identity\IdentityInterface;

/**
 *
 * @author vlse2610
 */
interface IdentityMapInterface {
    
    //   $identityMapIndex pro primarni klic
    //   $identityMapIndex pro cizi klic
    //   ...atd  NEBO JEN JEDEN ?
    
    
    
    public function add ( /*IdentityInterface $identity ??*/ $index, EntityInterface $entity  ) : void    ;
    
    public function get ( /*IdentityInterface $identity ??*/ $index) : ?EntityInterface ;
    
    public function remove ( /*IdentityInterface $identity ??*/ $index ) : void ;
    
    
    
    
}
