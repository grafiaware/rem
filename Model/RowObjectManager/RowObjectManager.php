<?php
namespace Model\RowObjectManager;


use Model\RowObjectManager\RowObjectManagerInterface;
use Model\RowObject\RowObjectInterface;

use Model\Testovaci\RowObject\RabbitRowObject;
use Model\Testovaci\Key\RabbitKey;
use Model\RowObject\Key\KeyInterface;
//use Model\Testovaci\Key\RabbitKeyInterface;




/**
 * Description of TestovaciRowObjectManager
 *
 * @author vlse2610
 */
class RowObjectManager  implements RowObjectManagerInterface {  
    /**
    *
    * @var array
    */
    private $poleRowObjectu ;
    
    
    
    
    public function flush () : void {
        
    }
  
    public function get(  $hash  /* KeyInterface $key*/  )  :  ?RowObjectInterface {

        return $this->poleRowObjectu[ $key->getIndexFromKey() ] ?? NULL; 
    }
   
   
    public function getByForeignKey( KeyInterface $foreignKey  )  :  iterable /*?*/   {

        //return $this->poleRowObjectu[$key->getIndexFromKey()] ?? NULL; 
    }
    
    
    public function remove( RowObjectInterface $rowObject ): void {
        
        //$this->poleRowObjectu[$rowObject->getKey()->getIndexFromKey()]=null;
    }
    
    
    public function add( RowObjectInterface $rowObject): void {
        
        //$this->poleRowObjectu[$rowObject->getKey()->getIndexFromKey()] = $rowObject;
        
    }
    
    
    
//     public function getIndexFromIdentityHash( array $identityHash ): string  {
//        //$a = \get_object_vars($this); 
//        $b = ksort ($identityHash);
//        
//        $index="";
//        foreach (  $b   as $nameAttr=>$value ) {            
//           $index .= $value;                        
//        }
//        return $index;   
//             
//    } 
    
    
    
    
    
    // tady to asi nema byt konkretne jen pro rabbitta
    public function createRowObject (  ) : RowObjectInterface {
            $key = new RabbitKey();
        return new RabbitRowObject( [$key] );
    }
    
    
     // tady to asi nema byt konkretne jen pro rabbitta
    public function createKey (  ) : KeyInterface {
        return new RabbitKey( );
    }
    
    
   
    
}
