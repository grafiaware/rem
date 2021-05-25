<?php
namespace Model\Entity\Hydrator\Filter;

/**
 *
 * @author vlse2610
 */
interface ManyToOneFilterInterface  extends \IteratorAggregate {
  
 //   public function setConfig( array $poleJmen ) : void ;
        
    
    //Pozn.
    //getIterator vrací iterovatelný objekt.    
    
    /**    
     * Vrací jména, která budou použita k hydratovani/extrahování.
     * @return \Traversable
     */
    public function getIterator() : \Traversable;
    
}

