<?php
namespace Model\IdentityMap\IndexMaker;

use Model\IdentityMap\IndexMaker\IndexMakerInterface;
use Model\Entity\Identity\IdentityInterface;
use Model\RowObject\Key\KeyInterface;

/**
 * Description of IndexMaker
 *
 * @author vlse2610
 */

class IndexMaker implements IndexMakerInterface {
    
    
    
    /**
     * Filtr obsahuje jmena (vlastnosti identity) potřebná pro jména metod identity, která (ta jména)  se účastní výroby indexu.
     * 
     * @var array  
     */    
    private $filterIdentity;
    
    public function __construct(  array $filterProHydratovaniIdentity ) {
        $this->filterIdentity = $filterProHydratovaniIdentity;
    }  
         
    
   
    public  function IndexFromIdentity(   IdentityInterface $identity  ) : string {}    
    
    
    
//    public  function IndexFromParams( array $params  ) : string {}
//    public  function IndexFromKey( KeyInterface  $key  ) : string {}
}
