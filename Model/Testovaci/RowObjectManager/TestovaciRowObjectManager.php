<?php
namespace Model\Testovaci\RowObjectManager;

use Model\RowObjectManager\RowObjectManagerAbstract;

use Model\RowObjectManager\RowObjectManagerInterface;
use Model\RowObject\Key\KeyInterface;
use Model\RowObject\RowObjectInterface;

use Model\Testovaci\RowObject\TestovaciRowObject;
use Model\Testovaci\Key\TestovaciKey;



/**
 * Description of TestovaciRowObjectManager
 *
 * @author vlse2610
 */
class TestovaciRowObjectManager extends RowObjectManagerAbstract implements RowObjectManagerInterface {  
    /**
    *
    * @var array
    */
    private $poleRowObjectu ;
    
    
    
    
    public function flush () : void {
        
    }
  
    public function get( KeyInterface $key  )  :  ?RowObjectInterface {

        return $this->poleRowObjectu[$key->getIndexFromKey()];
    }
   
    
    public function remove( RowObjectInterface $rowObject ): void {
        
        $this->poleRowObjectu[$rowObject->getKey()->getIndexFromKey()]=null;
    }
    
    
    public function add( RowObjectInterface $rowObject): void {
        
        $this->poleRowObjectu[$rowObject->getKey()->getIndexFromKey()] = $rowObject;
        
    }
    
    
    
    
    
    public function createRowObject (  ) : RowObjectInterface {
            $key = new TestovaciKey();
        return new TestovaciRowObject( $key );
    }
    
    
    public function createKey (  ) : KeyInterface {
        return new TestovaciKey( );
    }
    
    
   
    
}
