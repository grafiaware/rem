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
    /**
     *
     * @var TestovaciRowObject 
     */
    private $schovavacka;
    
        
    public function flush () : void {
        
    }
        
    
    
    public function get( KeyInterface $key  )  :  ?RowObjectInterface {
//        $O = new TestovaciRowObject($key);
//        $O->prvekDatetime = new \DateTime('2000-01-01');
//        $O->prvekVarchar = '';
//        $O->celeJmeno = "Jméno Celé";
//        return $O;
        //return new TestovaciRowObject($key);
        return $this->schovavacka;
       
    }
    
   
    
    
    public function remove( RowObjectInterface $rowObject ): void {
        $this->schovavacka=null;
    }
    
   
    
    public function createRowObject (  ) : RowObjectInterface {
            $key = new TestovaciKey();
        return new TestovaciRowObject( $key );
    }
    
    
    public function createKey (  ) : KeyInterface {
        return new TestovaciKey( );
    }
    
    
    public function add( RowObjectInterface $rowObject): void {
        
        $this->schovavacka = $rowObject;
        
    }
    
}
