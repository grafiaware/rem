<?php

namespace Model\Entity\Hydrator\Filter;

/**
 *
 * @author vlse2610
 */
interface OneToOneExcludeFilterInterface  extends \IteratorAggregate {
  
 //   public function setConfig( array $poleJmen ) : void ;
        
    
    //Pozn.
    //getIterator vrací iterovatelný objekt.    
    
    /**    
     * Vrací jména, která budou použita k nastavování/extrahování.
     * @return \Traversable
     */
    public function getIterator() : \Traversable;
    
}
