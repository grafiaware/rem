<?php
namespace Model\Entity\Identity\Hydrator\__k\Hydrator;

use Model\Entity\Identity\__k\KeyInterface;
use Model\RowObject\RowObjectInterface;

    
interface KeyHydratorInterface {
   
//
//     * @param IdentityInterface $identity
//     * @param RowObjectInterface $rowObject
//     * @return void
//     * @throws \UnexpectedValueException
   
    /**
     * 
     * @param KeyInterface $key
     * @param RowObjectInterface $rowObject
     * @return void
     */
    public function hydrate( KeyInterface $key, RowObjectInterface $rowObject): void;
     
        
//     * @param IdentityInterface $identity
//     * @param RowObjectInterface $rowObject
//     * @return void
//     * @throws MissingAttributeFieldValueException
    
    
    
    /**
     * 
     * @param KeyInterface $key
     * @param RowObjectInterface $rowObject
     * @return void
     */ 
    public function extract(  KeyInterface $key, RowObjectInterface $rowObject): void;
    
}