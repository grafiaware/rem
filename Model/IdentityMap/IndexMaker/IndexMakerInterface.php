<?php
namespace Model\IdentityMap\IndexMaker;

use Model\Entity\Identity\IdentityInterface;
use Model\RowObject\Key\KeyInterface;


/**
 *
 * @author vlse2610
 */
interface IndexMakerInterface {
    
    
    public static function IndexFromParams( array $params  ) : string;
    public static function IndexFromKey( KeyInterface  $key  ) : string;
    public static function IndexFromIdentity( IdentityInterface $identity  ) : string;
    
    
}
