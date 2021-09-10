<?php
namespace Model\VS\RowObjectManager;

use Model\RowObjectManager\RowObjectManagerInterface;
use Model\RowObject\Key\KeyInterface;
use Model\RowObject\Key\Key;
use Model\RowObject\RowObjectInterface;
use Model\RowObject\RowObject;

use Model\VS\RowObject\TestovaciRowObject;



/**
 * Description of TestovaciRowObjectManager
 *
 * @author vlse2610
 */
class TestovaciRowObjectManager implements RowObjectManagerInterface {
        
    public function flush () : void {}
        
    //public function add( RowObjectInterface $rowObject ): void ;
           
    public function get( KeyInterface $key  )  :  RowObjectInterface {
        return new RowObject($key);
        
    }
    
    
    public function getKey(   )  : KeyInterface {
        return new Key();
    }
    
    
    public function remove( RowObjectInterface $rowObject ): void {
        
    }
    
               
    //public function createKey () : KeyInterface ;
    
    public function createRowObject (  ) : TestovaciRowObject {
            $key = new Key();
        return new TestovaciRowObject( $key );
    }
    
    public function addRowObject(  RowObjectInterface $rowObject): void {
        
    }
    
}
