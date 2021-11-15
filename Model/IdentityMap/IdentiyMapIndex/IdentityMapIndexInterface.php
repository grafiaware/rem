<?php
namespace Model\IdentityMap\IdentityMapIndex;

use Model\Entity\EntityInterface;

/**
 *
 * @author vlse2610
 */
interface IdentityMapIndexInterface {
   
    // $index
    
    public function add( $entity ) : void;
   
    public function get( $index ) :  EntityInterface ;
    
    public function remove( $index ) : void;
    
}
