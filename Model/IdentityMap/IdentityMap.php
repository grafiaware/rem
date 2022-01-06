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
 * IdentityMap
 * IdentityMapIndex je pro jednu identitu.
 *
 * @author vlse2610
 */
class IdentityMap implements IdentityMapInterface {
    /**
     *
     * @var IdentityMapIndexFactoryInterface
     */
    private $identityMapIndexFactory;
    
    
    /**
     *
     * @var IndexMakerInterface 
     */
    private $indexMaker;
    
   
    /**
     *
     * @var array
     */
    private $identityMapArray; 
    
    
    
   
    public function __construct(            
                    AccessorMethodNameHydratorInterface $methodNameHydrator           
                    /*OneToOneFilterInterface $filter,*/            
                    //IndexMakerInterface $indexMaker                                    
//                             IdentityMapIndexFactoryInterface  $identityMapIndexFactory,
                     ) {
       
             //++ vyrobit pro ?kazdou identity   $identityMapIndex do pole IdentityMap 
        
        //$this->identityMapIndexFactory = $identityMapIndexFactory;
        //$this->indexMaker = $indexMaker;
    }  
    
        
    /**
     * Přidá  $entity do vsech 'index seznamu'. Vsech = pro kazdou identitu.
     * @param EntityInterface $entity
     * @return void
     */    
    public function add (  EntityInterface $entity, IndexMakerInterface $indexMaker /*v něm přichystan filtr */  ) : void   {
        
        foreach ($entity->getIdentities() as $identityInterfaceName=>$identity) {
            $index = $indexMaker->IndexFromIdentity($identity);               
            $this->identityMapArray[$identityInterfaceName][$index] = $entity;
        }
        
        
        
        //        if (!array_key_exists( $identityInterfaceName,  $this->identityMapArray ) ) {
        //            $i = $this->identityMapIndexFactory->create();
        //            $this->identityMapArray[ $identityInterfaceName ]= $i;
        //        }
        
        // $index = $this->indexMaker->IndexFromIdentity( $entity->getIdentity( $identityInterfaceName ) );                      
        //$this->identityMap[ $identityInterfaceName ] ->add ( $index, $entity );
    }
    
    
    
    /**
     * 
     * @param IdentityInterface $identity
     * @param string $identityInterfaceName
     * @return EntityInterface|null
     */
    public function get (IdentityInterface $identity, string $identityInterfaceName ) : ?EntityInterface {
        
        // $identityMapIndex->get (index z $identity)
    }
    
    
    /**
     * 
     * @param EntityInterface $entity
     * @param string $identityInterfaceName
     * @return void
     */
    public function remove (  EntityInterface $entity , string $identityInterfaceName ) : void {
        
    }
    
    
    /**
     * 
     * @param IdentityInterface $identity
     * @param string $identityInterfaceName
     * @return boolean
     */
    public function has (  IdentityInterface $identity, string $identityInterfaceName ) : boolean {
        
    }
    
    
    
}
