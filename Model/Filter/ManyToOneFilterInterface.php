<?php
namespace Model\Filter;

/**
 *
 * @author vlse2610
 */
interface ManyToOneFilterInterface  extends FilterInterface {
  
 //   public function setConfig( array $poleJmen ) : void ;
        
    
    //Pozn.
    //getIterator vrací iterovatelný objekt.    
    
    /**    
     * Vrací jména, která budou použita k hydratovani/extrahování.
     * @return \Traversable
     */
    public function getIterator() : \Traversable;
    
}

