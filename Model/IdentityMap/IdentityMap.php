<?php
namespace Model\IdentityMap;

use Model\IdentityMap\IdentityMapInterface;
use Model\IdentityMap\IndexMaker\IndexMakerInterface;
use Model\Entity\EntityInterface;
use Model\Entity\Identity\IdentityInterface;




/**
 * Description of IdentityMap
 *
 * @author vlse2610
 */
class IdentityMap implements IdentityMapInterface {
    
    private $indexMaker;
    
    //Index seznamy - je jich vic podle poctu identit
    private $identityMapIndexies; //asi array (podle jmena identity)  objektů seznamu
    
   
    public function __construct(   $identitiesNames , IndexMakerInterface $indexMaker ) {
       
                  //foreach - vyrobit pro kazdou identity $identityMapIndex; do pole $identityMapIndexies array[' jmenoIdentity ']
    }  
        
    /**
     * Přidá  entity do vsech  index seznamu
     * @param EntityInterface $entity
     * @return void
     */
    public function add (  EntityInterface $entity  ) : void   {
        
        // $identityMapIndexies[  '' ] ->add ($index z $identity, $entity)
    }
    
    /**
     * Podle identity vyzvedne entitu z prislusneho seznamu
     * @param \Model\IdentityMap\IdentityInterface $identity
     * @return EntityInterface|null
     */
    public function get ( IdentityInterface $identity ) : ?EntityInterface {
        // instance of
        // $identityMapIndexies[  '' ] ->get ($index z $identity)
    }
    
    public function remove ( EntityInterface $entity ) : void {
        
    }
    
    /**
     * Je  v Mape entita?
     * @param EntityInterface $entity
     * @return boolean
     */
    public function has ( EntityInterface $entity ) : boolean {
        
    }
    
    
    
}
