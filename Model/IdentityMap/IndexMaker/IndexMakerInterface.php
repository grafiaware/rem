<?php
namespace Model\IdentityMap\IndexMaker;

use Model\Entity\Identity\IdentityInterface;

//use Model\Filter\FilterInterface;

/**
 *
 * @author vlse2610
 */
interface IndexMakerInterface {
    
    /**
     * Vyrobi index z identity podle filtru.
     *      
     * @param IdentityInterface $identity
     * @param array $filter  Filtr obsahuje jmena (vlastnosti identity) potřebná pro jména metod identity, které   se účastní výroby indexu.
     * @return string
     */
    public  function indexFromIdentity(  IdentityInterface $identity , array $filters ) : string;
    

}
