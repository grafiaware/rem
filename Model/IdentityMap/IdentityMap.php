<?php
namespace Model\IdentityMap;

use Model\IdentityMap\IdentityMapInterface;
use Model\IdentityMap\IndexMaker\IndexMakerInterface;
use Model\Entity\Identity\IdentityInterface;
use Model\Entity\EntityInterface;



/**
 * IdentityMap je pro entitu. Entita ma vice identit.
 * Seznam = jednorozmerne pole pro jednu identitu, klicem je index. 
 * $entityMap je dvourozmerne pole poli seznamu. prvnim klicem je $identityInterfaceName.
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
     * Dvou rozmerne pole. Obsahuje 'seznamy' entit. Pro kazdou identitu jeden seznam.
     * Seznam pro identitu se identifikuje prvnim rozmerem pole.
     * 
     * @var array
     */
    private $entityMap; 
    
    
    
   /**
    * 
    * @param IndexMakerInterface $indexMaker
    * @param array $filters  Filtry pro hydrataci method names v identitach.
    */
    public function __construct( IndexMakerInterface $indexMaker,                                    
                                 array $filters                                                   
                               ) {               
        $this->indexMaker = $indexMaker;
        $this->identityFilters = $filters;
    }  
    
        
    /**
     * Přidá  $entity do vsech 'index seznamu'  $entityMapy. Vsech = pro kazdou identitu.
     * @param EntityInterface $entity
     * @return void
     */    
    public function add ( EntityInterface $entity ) : void   {        
        foreach ($entity->getIdentities() as $identityInterfaceName=>$identity) {      
            $index = $this->index($identity, $identityInterfaceName);                     
            $this->entityMap[$identityInterfaceName][$index] =  $entity; 
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
        return $this->entityMap[$identityInterfaceName][$index]??null; // ?? - tzn. null coalescing operator 
    }
    
    
    /**
     * 
     * @param EntityInterface $entity
     * @return void
     */
    public function remove(EntityInterface $entity): void {
        foreach ($entity->getIdentities() as $identityInterfaceName=>$identity) {      
            $index = $this->index($identity, $identityInterfaceName);               
            if ( isset($this->entityMap[$identityInterfaceName][$index]) ) {
                unset ($this->entityMap[$identityInterfaceName][$index] )  ;                 
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
    public function has (  IdentityInterface $identity, string $identityInterfaceName ) : boolean {
        $index = $this->index($identity, $identityInterfaceName);               
        return isset( $this->entityMap[$identityInterfaceName][$index] ) ? true : false; 
    }
    
    private function index($identity, $identityInterfaceName) {
        return $this->indexMaker->indexFromIdentity($identity, $this->identityFilters[$identityInterfaceName]);  
    }
    
}
