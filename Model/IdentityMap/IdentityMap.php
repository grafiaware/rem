<?php
namespace Model\IdentityMap;

use Model\IdentityMap\IdentityMapInterface;
use Model\IdentityMap\IndexMaker\IndexMakerInterface;
use Model\Entity\Identity\IdentityInterface;
use Model\Entity\EntityInterface;



/**
 * IdentityMap je pro entitu. Entita ma vice identit.
 * Index seznam = jednorozmerne pole pro jednu identitu, klicem je index. 
 * $entityMap je dvourozmerne pole poli index seznamu. Prvnim klicem je typ identity $identityInterfaceName.
 *
 * @author vlse2610
 */
class IdentityMap implements IdentityMapInterface {   
    
    /**
     *
     * @var IndexMakerInterface 
     */
    private $indexMaker;
    
   
    private $identityFilters;
    
    
    /**
     * Dvourozmerne pole [$identityInterfaceName][$index]. Obsahuje 'seznamy' entit. Pro kazdou identitu jeden seznam entit.
     * Seznam pro identitu se identifikuje prvnim rozmerem pole.
     * 
     * @var array [$identityInterfaceName][$index]
     */
    private $identityMap; 
    
    
    
   /**
    * 
    * @param IndexMakerInterface $indexMaker
    * @param array $filters  Filtry pro hydrataci method names v identitach.
    */
    public function __construct( IndexMakerInterface $indexMaker,                                    
                                 array $filters                  ) {               
        $this->indexMaker = $indexMaker;
        $this->identityFilters = $filters;
    }  
    
    
    private function index($identity, $identityInterfaceName) {
        return $this->indexMaker->indexFromIdentity($identity, $this->identityFilters[$identityInterfaceName]);  
    }
    
    
    /**
     * Přidá  $entity do vsech 'index seznamu'  $entityMapy. Vsech = pro kazdou identitu.
     * @param EntityInterface $entity
     * @return void
     */    
    public function add ( EntityInterface $entity ) : void   {        
        foreach ($entity->getIdentities() as $identityInterfaceName=>$identity) {      
            $index = $this->index($identity, $identityInterfaceName);                           
            $this->identityMap[$identityInterfaceName][$index] =  $entity; 
        }                     
    }
    
    
    
    /**
     * Podle $identity  vyzvedne entitu.
     * 
     * @param IdentityInterface $identity
     * @param string $identityInterfaceName
     * @return EntityInterface|null
     */
    public function get(IdentityInterface $identity, string $identityInterfaceName): ?EntityInterface {        
        $index = $this->index($identity, $identityInterfaceName);               
        return $this->identityMap[$identityInterfaceName][$index]??null; // - tzn. null coalescing operator 
    }
    
    
    /**
     * 
     * @param EntityInterface $entity
     * @return void
     */
    public function remove(EntityInterface $entity): void {
        foreach ($entity->getIdentities() as $identityInterfaceName=>$identity) {      
            $index = $this->index($identity, $identityInterfaceName);               
            if ( isset($this->identityMap[$identityInterfaceName][$index]) ) {
                unset ($this->identityMap[$identityInterfaceName][$index] )  ;                 
            }
        }        
        
    }
    
    
    
    /**
     * Je v Mape entita?
     * +
     * @param IdentityInterface $identity
     * @param string $identityInterfaceName
     * @return boolean
     */
    public function has (  IdentityInterface $identity, string $identityInterfaceName ) : bool {
        $index = $this->index($identity, $identityInterfaceName);               
        return isset( $this->identityMap[$identityInterfaceName][$index] ) ? true : false; 
    }
    
   
}
