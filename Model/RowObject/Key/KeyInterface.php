<?php

namespace Model\RowObject\Key;

use Model\RowObject\AttributeInterface;

/**
 *
 * @author vlse2610
 */
interface KeyInterface  extends AttributeInterface{
   
     public function getIndexFromKeyRowObject() : array ; 
     
}
