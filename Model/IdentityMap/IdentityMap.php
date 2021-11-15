<?php
namespace Model\IdentityMap;

use Model\IdentityMap\IdentityMapInterface;
use Model\IdentityMap\IdentityMapIndex\IdentityMapIndexInterface;



/**
 * Description of IdentityMap
 *
 * @author vlse2610
 */
class IdentityMap implements IdentityMapInterface {
    /**
     *
     * @var IdentityMapIndexInterface
     */
    private $identityMapIndex; //asi array
    
   
    public function __construct(  ) {
                             
    }  
        
    
    public function add ( /*IdentityInterface $identity ??*/ $index, EntityInterface $entity  ) : void   {
        
    }
    
    public function get ( /*IdentityInterface $identity ??*/ $index) : ?EntityInterface {
        
    }
    
    public function remove ( /*IdentityInterface $identity ??*/ $index ) : void {
        
    }
    
    
}
