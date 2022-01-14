<?php
namespace Model\Filter;

/**
 *
 * @author vlse2610
 */
interface OneToManyFilterInterface  extends FilterInterface {
      
    //Pozn.
    //getIterator vrací iterovatelný objekt.    
    
    /**    
     * Vrací jména, která budou použita k nastavování/extrahování.
     * @return \Traversable
     */
    public function getIterator() : \Traversable;
    
}
