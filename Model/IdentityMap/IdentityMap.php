<?php
namespace Model\IdentityMap;

use Model\IdentityMap\IdentityMapInterface;
use Model\IdentityMap\IndexMaker\IndexMakerInterface;
use Model\Entity\EntityInterface;
use Model\Entity\Identity\IdentityInterface;
use Model\IdentityMap\IdentityMapIndex\IdentityMapIndexFactoryInterface;




/**
 * IdentityMap je pro jednu identitu.
 *
 * @author vlse2610
 */
class IdentityMap implements IdentityMapInterface {
    
    private $indexMaker;
    
   //Index seznam - je jeden.
    private $identityMapIndex; 
    
    private $identityMapIndexFactory;
    
   
    public function __construct(            /*$identitiesNames_seznamTypuIdentit,*/   /* \IteratorAggregate \Traversable*/            
            
                    IdentityMapIndexFactoryInterface  $identityMapIndexFactory,
                    IndexMakerInterface $indexMaker ) {
       
             //++ vyrobit pro kazdou identity   $identityMapIndex
    }  
    
        
   
    public function add (  EntityInterface $entity  ) : void   {
        
        // $identityMapIndex ->add ( $indexMaker->index z $identity,  )
    }
    
    
    
    
    public function get ( IdentityInterface $identity ) : ?EntityInterface {
        
        // $identityMapIndex->get (index z $identity)
    }
    
    
    
    public function remove ( EntityInterface $entity ) : void {
        
    }
    
    
    
    public function has (  IdentityInterface $identity ) : boolean {
        
    }
    
    
    
}
