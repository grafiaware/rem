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
    
    private $filterIdentity;
    
    // filtr obsahuje jmena vlastnosti identity ucastnicich se vyroby indexu
    public function __construct(  $filterProHydratovaniIdentity ) {
        
    }  
    
    public  function IndexFromParams( array $params  ) : string {}
    public  function IndexFromKey( KeyInterface  $key  ) : string {}
   
    public  function IndexFromIdentity(   IdentityInterface $identity  ) : string {}
}
