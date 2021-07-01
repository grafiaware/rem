<?php
namespace Model\Hydrator;

use Model\Entity\AccessorInterface;
use Model\RowObject\AttributeInterface;


/**
 *
 * @author vlse2610
 */
interface AccessorHydratorInterface {   
    /**
     * Hydratuje objekt entity hodnotami  z row objectu.
     * 
     * @param AccessorInterface $entity
     * @param AttributeInterface $rowObject
     * @return void
     */
    public function hydrate( AccessorInterface $entity, AttributeInterface $rowObject ): void;    
    
   
     /**
      * Extrahuje hodnoty z objektu entity do row objectu. 
      *     
      * @param AccessorInterface $entity
      * @param AttributeInterface $rowObject
      * @return void
      */
    public function extract ( AccessorInterface $entity, AttributeInterface $rowObject ): void;      
           
    
    
//    nepouzito
//    public function setFilter (  $filter ) : void; 
//    
//    public function getFilter(  ) :FilterInterface ;
         
    
}