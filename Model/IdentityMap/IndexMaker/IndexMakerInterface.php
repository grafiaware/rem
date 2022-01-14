<?php
namespace Model\IdentityMap\IndexMaker;

use Model\Entity\Identity\IdentityInterface;

use Model\Filter\FilterInterface;

/**
 *
 * @author vlse2610
 */
interface IndexMakerInterface {
    
    /**
     * Vyrobi index z identity podle filtru.
     * 
     * @param IdentityInterface $identity
     * @param FilterInterface $filter
     * @return string
     */
    public  function indexFromIdentity(  IdentityInterface $identity , FilterInterface $filter ) : string;
    

}
