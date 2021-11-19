<?php
namespace Model\IdentityMap\IdentityMapIndex;

use Model\IdentityMap\IdentityMapIndex\IdentityMapIndexInterface;
use Model\Entity\EntityInterface;



/**
 * Description of IdentitzMapIndex
 * 
 *
 * @author vlse2610
 */
class IdentityMapIndex implements IdentityMapIndexInterface {
    
   
    
    /**
     * pole assoc pole  index(vznikly z identity)->entity
     * @var array 
     */
    private $identityIndex;
    
    
    public function __construct(   ) {
        
    }  
    
    
    
    public function add( $index, EntityInterface $entity ) : void{
        $identityIndex[ $index ] = $entity;
    }
   
    public function get( $index ) :  EntityInterface {
        return $identityIndex[ $index ] ;
    }
    
    
    public function remove( $index ) : void {}
}
