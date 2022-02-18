<?php
//namespace Model\Hydrator;

use Model\RowObject\RowObjectInterface;
use Model\Entity\AccessorInterface;

use Model\Filter\FilterInterface;
use Model\Hydrator\NameHydrator\SetMethodNameHydrator;
use Model\Hydrator\NameHydrator\GetMethodNameHydrator;

/**
 * Bezstavový?? hydrátor
 * 
 */
class OneToOneEntityHydratorPostaru  implements EntityHydratorInterface {
    
    /**
     * Filtr obsahuje seznam jmen  vlastností row objektu k hydrataci/extrakci.
     * 
     * @var  FilterInterface    -  extends \IteratorAggregate
     */
    private $filter;
        
    public function __construct ( FilterInterface $filter  ) { 
        $this->filter = $filter;       
    }
    
    
    
    /**
     * Hydratuje objekt entity hodnotami z row objectu.
     * @param AccessorInterface $entity
     * @param RowObjectInterface $rowObject
     * @param OneToOneFilterInterface $filter
     * @return void
     */
    public function hydrate( AccessorInterface $entity, RowObjectInterface $rowObject ): void {        
        
        $setMethodNameHydrator = new SetMethodNameHydrator();                       
        foreach ( $this->filter as  /* $key =>*/ $value ) { //=> jmeno vlastnosti row objektu
            $methodName = $setMethodNameHydrator->hydrateEntity( $value );
            $entity->$methodName( $rowObject->$value );
        }
        
    }    
    
   
     
    /**
     * Extrahuje hodnoty z objektu entity do row objectu.      * 
     * 
     * @param AccessorInterface $entity
     * @param RowObjectInterface $rowObject
     * @param OneToOneFilterInterface $filter
     * @return void
     */
    public function extract ( AccessorInterface $entity, RowObjectInterface $rowObject  ): void {       
        
        $getMethodNameHydrator = new GetMethodNameHydrator();
        foreach ( $this->filter as /*$key =>*/ $value ) {  //=> jmeno vlastnosti row objektu
            $methodName = $getMethodNameHydrator->extract( $value );
            $rowObject->$value = $entity->$methodName() ;
        }
        
    }
    
    
     
//    public function setFilter ( /*OneToOne*/FilterInterface $filter ) :void{
//        $this->filter = $filter ;
//    } 
//    
//    
//    public function getFilter(  ) : /*OneToOne*/FilterInterface { 
//        return $this->filter ;
//    }
    
             
}    
    