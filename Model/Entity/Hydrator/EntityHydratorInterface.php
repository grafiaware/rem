<?php
namespace Model\Entity\Hydrator;

use Model\Entity\EntityInterface;
use Model\RowObject\RowObjectInterface;


/**
 *
 * @author vlse2610
 */
interface EntityHydratorInterface {   
    /**
     * Hydratuje objekt entity hodnotami  z row objectu.
     * 
     * @param EntityInterface $entity
     * @param RowObjectInterface $rowObject
     * @return void
     */
    public function hydrate( EntityInterface $entity, RowObjectInterface $rowObject ): void;    
    
   
     /**
      * Extrahuje hodnoty z objektu entity do row objectu. 
      *     
      * @param EntityInterface $entity
      * @param RowObjectInterface $rowObject
      * @return void
      */
    public function extract ( EntityInterface $entity, RowObjectInterface $rowObject ): void;      
           
    
    
//    nepouzito
//    public function setFilter (  $filter ) : void; 
//    
//    public function getFilter(  ) :FilterInterface ;
         
    
}