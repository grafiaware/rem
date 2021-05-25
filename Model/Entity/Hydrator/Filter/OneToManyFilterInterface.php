<?php
namespace Model\Entity\Hydrator\Filter;

/**
 *
 * @author vlse2610
 */
interface OneToManyFilterInterface  extends \IteratorAggregate {
      
    //Pozn.
    //getIterator vrací iterovatelný objekt.    
    
    /**    
     * Vrací jména, která budou použita k nastavování/extrahování.
     * @return \Traversable
     */
    public function getIterator() : \Traversable;
    
}
