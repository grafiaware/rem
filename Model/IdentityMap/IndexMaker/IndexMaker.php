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
    
    //do konstructoru filtrProhydratIdentity
    
    public  function IndexFromParams( array $params  ) : string {}
    public  function IndexFromKey( KeyInterface  $key  ) : string {}
   
    public  function IndexFromIdentity( /*nebo filtrProhydratorIdentity,*/ IdentityInterface $identity  ) : string {}
}
