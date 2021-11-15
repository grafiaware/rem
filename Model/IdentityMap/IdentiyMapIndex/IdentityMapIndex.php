<?php
namespace Model\IdentityMap\IdentityMapIndex;

use Model\IdentityMap\IdentityMapIndex\IdentityMapIndexInterface;



/**
 * Description of IdentitzMapIndex
 *
 * @author vlse2610
 */
class IdentityMapIndex implements IdentityMapIndexInterface {
    
    public function add( $entity ) : void{}
   
    public function get( $index ) :  EntityInterface {}
    
    public function remove( $index ) : void {}
}
