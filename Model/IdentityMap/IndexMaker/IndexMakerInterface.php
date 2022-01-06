<?php
namespace Model\IdentityMap\IndexMaker;

use Model\Entity\Identity\IdentityInterface;
use Model\RowObject\Key\KeyInterface;


/**
 *
 * @author vlse2610
 */
interface IndexMakerInterface {
    
    
    
    /**
     * Vyrobi index z vlastnosti identity podle filtru 
     * 
     * @param IdentityInterface $identity aaa
     * @return string
     */
    public  function IndexFromIdentity(  IdentityInterface $identity  ) : string;
    
    
    
//    public  function IndexFromParams( array $params  ) : string;
//    public  function IndexFromKey( KeyInterface  $key  ) : string;
}
