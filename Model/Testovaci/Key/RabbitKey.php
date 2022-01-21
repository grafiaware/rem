<?php
namespace Model\Testovaci\Key;

use Model\RowObject\Key\KeyAbstract;
use Model\Testovaci\Key\RabbitKeyInterface;



/**
 * Description of Key
 *
 * @author vlse2610
 */
class RabbitKey extends KeyAbstract implements RabbitKeyInterface {
    /**
     *
     * @var string
     */
    public $id1;
   
    
    //-------------------------
    
    public function getTypeKey(): string{
        return RabbitKeyInterface::class;
    }
    
   
  
}
