<?php
namespace Model\Entity\Hydrator;

use Model\Entity\EntityInterface;
use Model\RowObject\RowObjectInterface;

use Model\Entity\Hydrator\Filter\OneToOneFilterInterface;
use Model\Entity\Hydrator\NameHydrator\MethodNameHydratorInterface;
use Model\Entity\Hydrator\EntityHydratorInterface;


/**
 * Bezstavový?? hydrátor
 * 
 */
class OneToOneEntityHydrator implements EntityHydratorInterface {
    /**
     *
     * @var MethodNameHydratorInterface
     */
    private $methodNameHydrator;
    
    /**
     * Filtr obsahuje seznam jmen (pole jmen)  vlastností row objektu k hydrataci/extrakci.
     * 
     * @var  OneToOneFilterInterface    -  extends \IteratorAggregate
     */
    private $filter;
        
    
    public function __construct ( MethodNameHydratorInterface $methodNameHydrator,  OneToOneFilterInterface $filter  ) { 
        $this->methodNameHydrator = $methodNameHydrator;
        $this->filter = $filter;       
    }     
   
     
    /**
     * Hydratuje objekt entity hodnotami z row objectu.
     * 
     * @param EntityInterface $entity
     * @param RowObjectInterface $rowObject
     * @return void
     */
    public function hydrate( EntityInterface $entity, RowObjectInterface $rowObject ): void {        
        foreach ( $this->filter as $name ) {      //=> jmeno vlastnosti row objektu        
            $methodName = $this->methodNameHydrator->hydrate( $name );
            $entity->$methodName( $rowObject->$name );
        }        
    }    
       
     
    /**
     * Extrahuje hodnoty z objektu entity do row objectu.
     * 
     * @param EntityInterface $entity
     * @param RowObjectInterface $rowObject
     * @return void
     */
    public function extract ( EntityInterface $entity, RowObjectInterface $rowObject ): void {       
        foreach ( $this->filter as $name )  {   //=> jmeno vlastnosti row objektu                  
            $methodName = $this->methodNameHydrator->extract( $name );
            $rowObject->$name = $entity->$methodName() ;
        }        
    }
    
}


// nepouzito     
//    public function setFilter ( /*OneToOne*/FilterInterface $filter ) :void{
//        $this->filter = $filter ;
//    }  
//    public function getFilter(  ) : /*OneToOne*/FilterInterface { 
//        return $this->filter ;
//    }

//         // PRAVDY   
//         //rowObject ma public vlastnosti !!!!!!!!
//         //entity    ma private vlastnosti a set-get-ry  
        
//    (pozn. $data[NULL] -> offsetSet(index, NULL) ....
              
    
    
    
////-------------------------------------------------------------------------------------------------------------
////--------------------------------------------------------------------------------------------------------------    
//    /**
//     * Hydratuje objekt $entity hodnotami objektu $rowObject voláním $entity->setter metod.
//     * 
//     * Neřídí se vlastnostmi entity. Vlastnosti určené k hydrataci (zjistí se z filtru $this->EntityHydratorFiltr) hydratuje.
//     * 
//     * @param EntityInterface $entity
//     * @param RowObjectInterface $rowObject
//     * @throws \UnexpectedValueException
//     */
//    public function hydrate( EntityInterface $entity, RowObjectInterface $rowObject ) {        
////        foreach (get_class_methods(get_class($entity)) as $methodName) {
////            if (strpos($methodName, 'set') === 0) {
////                $entity->$methodName($value);        }     }        
//       
//        //$poleVlastnostiKHydrataci = $this->entityHydratorFiltr->getSeznamVlastnostiZRowOKHydrataciEntity();
//
//        $jmenoTridy = get_class($entity);
//        $jmenoParentTridy = get_parent_class($entity);
//        $jmenaMetod = get_class_methods(get_class($entity));
//        // $entity prijde (prazdne nenaplnene) s vlastnostmi "naplnenymi" = null
////        foreach (get_class_methods(get_class($entity)) as $methodName) {
////            if (strpos($methodName, 'set') === 0) {
////                
////                if (in_array($nazevVlastnostiEntity, $poleVlastnostiKHydrataci ) ) {
////                 //kdyz je vlastnost entity ve filtru hydratoru povolena k hydratovani, snazim se hydratovat   
////                $entity->$methodName(     );     
////                }                  
////            }                
////        //  ========================   ANI ZDALEKA NENI HOTOVE
////        }
////        // $entity prijde (prazdne nenaplnene) s vlastnostmi "naplnenymi" = null
////        $classMethods = get_class_methods(get_class($entity));  //pole názvů metod entity   
////        $poleVlastnostiKHydrataci = $this->entityHydratorFiltr->getPoleVlastnostiKHydrataci();
////        #####
////        $vlastnostiEntity = get_object_vars($entity);
////        foreach ( $vlastnostiEntity  /*$entity*/ as $nazevVlastnostiEntity=>$hodnotaVlastnostiEntity ) {
////            $setMethodName = 'set' . $this->underscoreToPascalCase($nazevVlastnostiEntity); //raz_dva  RazDva, jmeno metody set
////            
////            if (in_array($nazevVlastnostiEntity, $poleVlastnostiKHydrataci ) ) {
////                //kdyz je vlastnost entity ve filtru hydratoru povolena k hydratovani, snazim se hydratovat                    
////                if (in_array( $setMethodName, $classMethods))  {    // a kdyz set metoda v entity existuje                                                                                
////                    $entity->$setMethodName($rowObject->$nazevVlastnostiEntity);
////                } else {   // metoda entity set...vlastnost neexistuje   
////                    throw new \UnexpectedValueException("Entita " .  get_class($entity) .  " nemá metodu $setMethodName ->nelze hydratovat.");
////                }                                 
////            } else {    /* neni chyba,  ...jen nechci hydratovat tuto vlastnost */                 
////            }             
////        }
//    }
//    /**
//     * Extrahuje hodnoty z objektu $entity voláním $entity->getter metod ---> do objektu $rowObject voláním $rowObject->$setter metod.
//     * 
//     * Řídí se vlastnostmi entity. 
//     * Vlastnosti entity určené k extrahování  (zjistí se z filtru $this->EntityHydratorFiltr) extrahuje.   
//     * Hodnotu existující vlastnosti objektu $rowObject přepíše, neexistující vlastnosti $rowObject-u  NEpřidá. 
//     * Vlastnosti navíc v objektu $rowObject nevadí ( řídí se vlastnostmi entity ).
//     * 
//     * @param TableEntityInterface $entity
//     * @param RowObjectInterface $rowObject
//     * @throws \UnexpectedValueException
//     *     
//     */
//    public function extract( EntityInterface $entity, RowObjectInterface $rowObject) { 
//        
////  ==================   ANI ZDALEKA NENI HOTOVE ================ neexistuji sety gety row objektu!!!!!!!!!!!!!!!!!!
//        
////        $classMethodsEntity = get_class_methods(get_class( $entity ));
////        $classMethodsObject = get_class_methods(get_class( $rowObject ));
////        $poleVlastnostiKExtrakci = $this->entityHydratorFiltr->getPoleVlastnostiKExtrakci();
////        #####
////        foreach ( $entity as $nazevVlastnostiEntity=>$hodnotaVlastnostiEntity ) {    // vsechny názvy metod entity
////            // z entity stehuju do $rowObject
////            $getMethodName = 'get' . $this->underscoreToPascalCase($nazevVlastnostiEntity); //raz_dva  RazDva, jmeno metody get
////            $setMethodName = 'set' . $this->underscoreToPascalCase($nazevVlastnostiEntity);
////            
////            if (in_array($nazevVlastnostiEntity, $poleVlastnostiKExtrakci ) ) {
////                //kdyz je vlastnost entity ve filtru hydratoru povolena k extrakci, snazim se extrahovat                  
////                if (in_array( $getMethodName, $classMethodsEntity))  {    // a kdyz get metoda v entity existuje                                                                                
////                   $hodnotaVlastnostiEntity = $entity->$getMethodName( ); //hodnota k extrakci do rowObjectu
////                   // $hodnotaVlastnostiEntity
////                   if (in_array( $setMethodName, $classMethodsObject) ) { // a kdyz set metoda v rowObject existuje                        
////                           $rowObject->$setMethodName ( $hodnotaVlastnostiEntity );
////                   }
////                   else {
////                       //neexistuje set metoda objektu $rowObject -- tak nic neprida --OK
////                   }                                                         
////                } else {   // metoda entity get...vlastnosti neexistuje   
////                    throw new \UnexpectedValueException("Entita " .  get_class($entity) .  " nemá metodu $getMethodName ->nelze extrahovat.");
////                } 
////                                
////            }else {    /* neni chyba,  ...jen nechci hydratovat tuto vlastnost */                 
////            } 
//    private function camelCaseToUnderscore($camelCaseName) {
//        $pom = strtolower(preg_replace('/(?<!^)([A-Z])/', '_$1', $camelCaseName));
//        return strtolower(preg_replace('/(?<!^)([A-Z])/', '_$1', $camelCaseName));  // RazDva -> raz_dva
//    }
//
//    private function underscoreToPascalCase($underscoredName){   // první písmeno velké  // raz_dva -> RazDva
//        $pom = str_replace(' ', '', ucwords(str_replace('_', ' ', $underscoredName)));
//        return str_replace(' ', '', ucwords(str_replace('_', ' ', $underscoredName)));
//    }
//    /**
//     * 
//     * @param EntityHydratorFiltrVse $entityHydratorFiltr
//     */
//    public function setFiltr(  EntityHydratorFiltrVse $entityHydratorFiltr ) {
//        $this->entityHydratorFiltr = $entityHydratorFiltr;
//    }
//}

