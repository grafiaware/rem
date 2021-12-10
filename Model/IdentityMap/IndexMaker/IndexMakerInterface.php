<?php
namespace Model\IdentityMap\IndexMaker;

use Model\Entity\Identity\IdentityInterface;
use Model\RowObject\Key\KeyInterface;


/**
 *
 * @author vlse2610
 */
interface IndexMakerInterface {
    // filtr obsahuje jmena vlastnosti identity ucastnicich se vyroby indexu
    //public function __construct(  $filterProHydratovaniIdentity ) {
        
  
    
    public static function IndexFromParams( array $params  ) : string;
    public static function IndexFromKey( KeyInterface  $key  ) : string;
    
    
    /**
     * Vyrobi index z vlastnosti identity.
     * @param IdentityInterface $identity
     * @return string
     */
    public static function IndexFromIdentity( /* typ identity,*/ IdentityInterface $identity  ) : string;
    
    
}
