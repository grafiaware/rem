<?php
namespace Model\IdentityMap\IndexMaker;

use Model\IdentityMap\IndexMaker\IndexMakerInterface;


/**
 * Description of IndexMaker
 *
 * @author vlse2610
 */
class IndexMaker implements IndexMakerInterface {
    
    public static function IndexFromParams( array $params  ) : string {}
    public static function IndexFromKey( KeyInterface  $key  ) : string {}
    public static function IndexFromIdentity( IdentityInterface $identity  ) : string {}
}
