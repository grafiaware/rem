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
class TestovaciRowObjectManager extends RowObjectManagerAbstract /*TAM NIC NENI*/ implements RowObjectManagerInterface {  
    /**
    *
    * @var array
    */
    private $poleRowObjectu ;
    
    
    
    
    public function flush () : void {
        
    }
  
    public function get(   $identityHash /*KeyInterface $key */ )  :  ?RowObjectInterface {

        return $this->poleRowObjectu[ $key->getIndexFromKey() ] ?? NULL; 
    }
   
   
    public function getByForeignKey( KeyInterface $foreignKey  )  :  ?RowObjectInterface {

        //return $this->poleRowObjectu[$key->getIndexFromKey()] ?? NULL; 
    }
    
    
    public function remove( RowObjectInterface $rowObject ): void {
        
        $this->poleRowObjectu[$rowObject->getKey()->getIndexFromKey()]=null;
    }
    
    
    public function add( RowObjectInterface $rowObject): void {
        
        $this->poleRowObjectu[$rowObject->getKey()->getIndexFromKey()] = $rowObject;
        
    }
    
    
    
     public function getIndexFromIdentityHash( array $identityHash ): string  {
        //$a = \get_object_vars($this); 
        $b = ksort ($identityHash);
        
        $index="";
        foreach (  $b   as $nameAttr=>$value ) {            
           $index .= $value;                        
        }
        return $index;   
             
    } 
    
    
    
    
    
    
//    public function createRowObject (  ) : RowObjectInterface {
//            $key = new TestovaciKey();
//        return new TestovaciRowObject( $key );
//    }
//    
//    
//    public function createKey (  ) : KeyInterface {
//        return new TestovaciKey( );
//    }
    
    
   
    
}
