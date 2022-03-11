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
       
    /**
     * 
     * @param AccessorMethodNameHydratorInterface $methodNameHydrator
     */
    public function __construct( accessorMethodNameHydratorInterface $methodNameHydrator ) {
        $this->methodNameHydrator = $methodNameHydrator;
    }  

    
    /**
     * Vyrobi index z identity podle filtru.
     *      
     * @param IdentityInterface $identity
     * @param array $filters  Filtr obsahuje jmena (vlastnosti identity) potřebná pro jména metod identity, které   se účastní výroby indexu.
     * @return string
     */
    public function indexFromIdentity(IdentityInterface $identity, $filter): string {
        $index = '';

            foreach ($filter as $name) {
                $methodName = $this->methodNameHydrator->extract($name);
                // dalo by se zkontrolovat ze se nevraci prazdny retezec - jako kontrola uplnosti identity
                // $identity->$methodName();                
            
                $index .= $identity->$methodName();                
            }

        return $index;
    }
}
