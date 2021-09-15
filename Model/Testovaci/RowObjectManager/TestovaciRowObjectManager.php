<?php
namespace Model\Testovaci\RowObjectManager;

use Model\RowObjectManager\RowObjectManagerInterface;
use Model\RowObject\Key\KeyInterface;
use Model\RowObject\Key\Key;
use Model\RowObject\RowObjectInterface;
use Model\RowObject\RowObject;

use Model\Testovaci\RowObject\TestovaciRowObject;
use Model\Testovaci\Key\TestovaciKey;



/**
 * Description of TestovaciRowObjectManager
 *
 * @author vlse2610
 */
class TestovaciRowObjectManager implements RowObjectManagerInterface {
        
    public function flush () : void {
        
    }
        
    
    
    public function get( KeyInterface $key  )  :  ?RowObjectInterface {
        return new RowObject($key);
        
    }
    
    
  
    
    
    public function remove( RowObjectInterface $rowObject ): void {
        
    }
    
   
    
    public function createRowObject (  ) : RowObjectInterface {
            $key = new Key();
        return new TestovaciRowObject( $key );
    }
    
    
    public function createKey (  ) : KeyInterface {
        return new TestovaciKey( );
    }
    
    
    public function add( RowObjectInterface $rowObject): void {
        
    }
    
}
