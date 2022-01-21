<?php
namespace Model\Testovaci\RowObject;

use Model\RowObject\RowObjectInterface;
use Model\RowObject\RowObjectAbstract;

//use Model\Testovaci\Key\TestovaciKey;

/**
 * Description of TestovaciRowObject
 *
 * @author vlse2610
 */
class RabbitRowObject extends RowObjectAbstract implements RowObjectInterface {
        /**
         * @var string
         */   
        public $celeJmeno;     
        
        
        /**
         *
         * @var string
         */
        public $prvekVarchar;    
       
        /**
         *
         * @var \DateTime 
         */
        public $prvekDatetime; 
    
//        
//        /**
//         * 
//         * @param TestovaciKey $key
//         */
//        public function __construct( TestovaciKey $key ) {
//            parent::__construct( $key );
//        } 
            
    }  
