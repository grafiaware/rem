<?php
namespace Model\IdentityMap;

use Model\IdentityMap\IdentityMapInterface;
use Model\IdentityMap\IndexMaker\IndexMakerInterface;
use Model\Entity\EntityInterface;
use Model\Entity\Identity\IdentityInterface;
use Model\IdentityMap\IdentityMapIndex\IdentityMapIndexFactoryInterface;

use Model\Hydrator\NameHydrator\AccessorMethodNameHydratorInterface;
use Model\Hydrator\Filter\OneToOneFilterInterface;




/**
 * IdentityMap je pro jednu identitu.
 *
 * @author vlse2610
 */
class IdentityMap implements IdentityMapInterface {
    private $identityMapIndexFactory;
    private $indexMaker;
    
   // Index seznam - je jeden.?????
   // array of $identityMapIndex
    private $identityMap; 
    
    
    
   
    public function __construct(            /*$identitiesNames_seznamTypuIdentit,*/   /* \IteratorAggregate \Traversable*/            
            
                    AccessorMethodNameHydratorInterface $methodNameHydrator, 
                    OneToOneFilterInterface $filter,
                        
            
                    IdentityMapIndexFactoryInterface  $identityMapIndexFactory,
                    IndexMakerInterface $indexMaker ) {
       
             //++ vyrobit pro kazdou identity   $identityMapIndex
        
        $this->identityMapIndexFactory = $identityMapIndexFactory;
    }  
    
        
   
    public function add (  EntityInterface $entity,  $index, string $identityInterfaceName ) : void   {
        
        if (!array_key_exists( '$identityInterfaceName',  $this->identityMap ) ) {
            $i = $this->identityMapIndexFactory->create();
            $this->identityMap[ '$identityInterfaceName' ]= $i;
        }
        
        $this->identityMap[ '$identityInterfaceName'] ->add ( $index, $entity );
    }
    
    
    
    
    public function get ( IdentityInterface $identity ) : ?EntityInterface {
        
        // $identityMapIndex->get (index z $identity)
    }
    
    
    
    public function remove ( EntityInterface $entity ) : void {
        
    }
    
    
    
    public function has (  IdentityInterface $identity ) : boolean {
        
    }
    
    
    
}
