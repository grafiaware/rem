<?php
namespace Model\RowObjectManager;

use Model\RowObject\RowObjectInterface;
use Model\RowObject\Key\KeyInterface;


/**
 *
 * @author vlse2610
 */
interface RowObjectManagerInterface {
    
    public function flush () : void ;
    

    
    public function addRowObject( RowObjectInterface $rowObject ): void ;
           
    public function getRowObject( KeyInterface $key  )  :  RowObjectInterface ;
    
    public function removeRowObject( RowObjectInterface $rowObject ): void ;
    
       
    
    
    public function createKey () : KeyInterface ;
    
    public function createRowObject ( KeyInterface $key ) : RowObjectInterface ;
       
    
}
