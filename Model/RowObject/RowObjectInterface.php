<?php
namespace Model\RowObject;

use Model\RowObject\AttributeInterface;
use Model\RowObject\Key\KeyInterface;

/**
 *
 * @author vlse2610
 */
interface RowObjectInterface extends AttributeInterface {
    
    
     /**
     * 
     * @return array[]
     */
    public function getKeys (): array;
    
       
    public function getKey( string $keyInterfaceName): KeyInterface; 
    
    
    
   
    
    
    
    
    
//    public function getForeignKey(): KeyInterface ;
//    public function setForeignKey( KeyInterface $key): KeyInterface ;
//    
    
    public function setPersisted(): void;
    
    public function setUnpersisted(): void ;
    
    public function isPersisted(): bool ;        
    
    
    public function lock(): void ;
    
    public function unLock(): void ;
    
    public function isLocked(): bool ;
           

    public function isChanged(): bool ;
    public function fetchChanged(): array ;

    

}
