<?php
namespace Model\IdentityMap\IndexMaker;

use Model\IdentityMap\IndexMaker\IndexMakerInterface;
use Model\Entity\Identity\IdentityInterface;
use Model\Filter\FilterInterface;

use Model\Hydrator\NameHydrator\AccessorMethodNameHydratorInterface;

/**
 * Description of IndexMaker
 *
 * @author vlse2610
 */

class IndexMaker implements IndexMakerInterface {

    /**
     * @var AccessorMethodNameHydratorInterface
     */
    private $methodNameHydrator;
       
    
    public function __construct( 
                            AccessorMethodNameHydratorInterface $methodNameHydrator
            ) {
        $this->methodNameHydrator = $methodNameHydrator;
    }  

    /**
     * Vyrobi index z identity podle filtru.
     * 
     * @param IdentityInterface $identity
     * @param FilterInterface $filter  Filtr obsahuje jmena (vlastnosti identity) potřebná pro jména metod identity, která (ta jména)  se účastní výroby indexu.
     * @return string
     */
    public function indexFromIdentity(IdentityInterface $identity, FilterInterface $filter): string {
        $index = '';
        foreach ($filter as $name) {
            $methodName = $this->methodNameHydrator->hydrate($name);
            $index .= $identity->$methodName();
        };
        return $index;
    }
}
