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
    

    
    //public function add( RowObjectInterface $rowObject ): void ;
           
    public function get( KeyInterface $key  )  :  RowObjectInterface ;
    public function getKey(   )  : KeyInterface  ;
    
    public function remove( RowObjectInterface $rowObject ): void ;
    
       
    
    
    //public function createKey () : KeyInterface ;
    
    public function createRowObject (  ) /*: RowObjectInterface*/ ;
    
    public function addRowObject(  RowObjectInterface $rowObject): void ;



       
    
}
