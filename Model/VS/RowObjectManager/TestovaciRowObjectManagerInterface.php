<?php

namespace Model\VS\RowObjectManager;

use Model\VS\RowObject\TestovaciRowObject;
use Model\RowObjectManager\RowObjectManagerInterface;

/**
 *
 * @author vlse2610
 */
interface TestovaciRowObjectManagerInterface extends RowObjectManagerInterface{        
    
 

    public function createRObject(   ) :  TestovaciRowObject ;
    
    
    
}
