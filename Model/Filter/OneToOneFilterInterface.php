<?php
namespace Model\Filter;

/**
 *
 * @author vlse2610
 */
interface OneToOneFilterInterface  extends FilterInterface {
  
 //   public function setConfig( array $poleJmen ) : void ;
        
    
    //Pozn.
    //getIterator vrací iterovatelný objekt.    
    
    /**    
     * Vrací jména, která budou použita k nastavování/extrahování.
     * @return \Traversable
     */
    public function getIterator() : \Traversable;
    
}
