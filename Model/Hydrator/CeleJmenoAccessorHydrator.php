<?php
namespace Model\Hydrator;

use Model\Hydrator\AccessorHydratorInterface;

use Model\Filter\OneToManyFilterInterface;
use Model\Hydrator\NameHydrator\AccessorMethodNameHydratorInterface;
use Model\Hydrator\NameHydrator\AccessorNameHydratorInterface;

use Model\Entity\AccessorInterface; 
use Model\RowObject\AttributeInterface;

/**
 * OneToMany /entity : rowobject/
 * @author vlse2610
 */
class CeleJmenoAccessorHydrator implements AccessorHydratorInterface {
    
    /**
     * Filtr pro CeleJmenoEntityHydrator obsahuje seznam jmen (pole jmen) pro vytvoreni jmena metody entity k hydrataci/extrakci a ke kazdemu jmenu prislusny seznam jmen  vlastnost√≠ row objektu.
     * 
     * @var  OneToManyFilterInterface    -  extends \IteratorAggregate
     */
    private $filter;
    /**
     *
     * @var AccessorNameHydratorInterface 
     */
    private $attributeNameHydrator;
    /**
     *
     * @var AccessorMethodNameHydratorInterface 
     */
    private $methodNameHydrator;
    /**
     *
     * @var CeleJmenoGluerInterface 
     */
    private $celeJmenoGluer;
    
            
    public function __construct ( AccessorNameHydratorInterface $attributeNameHydrator, AccessorMethodNameHydratorInterface $methodNameHydrator,
                                  OneToManyFilterInterface $filter, CeleJmenoGluerInterface $celeJmenoGluer ) { 
        $this->filter = $filter;       
        $this->attributeNameHydrator = $attributeNameHydrator;
        $this->methodNameHydrator  = $methodNameHydrator;
        $this->celeJmenoGluer = $celeJmenoGluer;
    }
          
    /**
     * Hydratuje objekt entity hodnotami  z $rowObjectu.
     * 
     * @param AccessorInterface $entity
     * @param RowObjectInterface $rowObject
     * @return void
     */
    public function hydrate( AccessorInterface $entity, AttributeInterface $rowObject): void {                                
        foreach ($this->filter as $key  => $projmenaVlastnostiRowObjektu) { // jmeno pro set metodu entity => jmena pro vlastnosti row objektu (v poradi, jak chci slepovat) )
            $methodName = $this->methodNameHydrator->hydrate( $key ); 
       
            $castiJmena = [];
            foreach ($projmenaVlastnostiRowObjektu as $projmenoVlastnosti) {  
                $propertyName = $this->attributeNameHydrator->hydrate($projmenoVlastnosti); // vytvoreni jmena vlastnosti row objectu
                $castiJmena[$projmenoVlastnosti] = $rowObject->$propertyName; //asoc. pole:    projmeno vlastn.rowObjectu => data z vlastn.rowObjectu 
            }            
            $celeJmeno = $this->celeJmenoGluer->stick($castiJmena, $projmenaVlastnostiRowObjektu);            
            $entity->$methodName( $celeJmeno ) ;            
        }   
    }
         
   
     /**
     * Extrahuje hodnoty z objektu $entity do $rowObjectu.     
     * 
     */
    public function extract ( AccessorInterface $entity, AttributeInterface $rowObject ): void {
        foreach ($this->filter as $key  => $list) { // jmeno pro metodu entity => jmena pro vlastnosti row objektu
            $methodName = $this->methodNameHydrator->extract( $key ); //metodou vyzvednu cele jmeno z Entity            
            $celeJmeno = $entity->$methodName(  ) ; //cte z entity
            
            $castiJmena = $this->celeJmenoGluer->unstick($celeJmeno, $list );  //asoc. pole:    projmeno vlastn.rowObjectu => data z vlastn.rowObjectu   
            foreach ($castiJmena as $projmenoVlastnostiRowObjektu => $value) {
                $propertyName = $this->attributeNameHydrator->extract($projmenoVlastnostiRowObjektu); // vytvoreni jmena vlastnosti row objectu                
                $rowObject->$propertyName = $value;
            }
        }               
    }       
            
             
 
    

//    public function setFilter ( /*OneToMany*/FilterInterface $filter ) :void {
//        $this->filter = $filter ;
//    }
//    
//    public function getFilter(  ) :  /*OneToMany*/FilterInterface {
//         return $this->filter ;
//    }




   
}
    
