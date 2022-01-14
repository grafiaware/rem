<?php
namespace Model\Filter;

use Model\Filter\ColumnFilterInterface;


 /**
 * Filtr pro hydrator typu RowObjectHydrator
 * 
 * @author vlse2610
 */
    class ColumnFilter implements ColumnFilterInterface {        
    /**
     *
     * @var array
     */    
    private $poleJmen;    
        
    
    
   /**
    * 
    * @param array $poleJmen
    */
    public function __construct( array $poleJmen ) {
        $this->poleJmen = $poleJmen;        
    }
    
    
    
    //Pozn. - getIterator vrací iterovatelný objekt.        
    /**
     * 
     * @return \Traversable
     */
    public function getIterator() : \Traversable {        
         return new \ArrayIterator( $this->poleJmen );
    }             


    

}



////zkontrolovat u pole generated naplneni pouze \True \FAlse
//        $okValue=true;
//        foreach ($generated as $value) {
//            if (!($value===true) and !($value===false)) {
//                $okValue = false;                 
//            }
//        }
//        if (!$okValue) {
//         throw new InvalidValueInGeneratedFieldException('Pole $generated obsahuje nepřípustné hodnoty.');
//        }   



        
        // zkontrolovat zda jména polí v $generated odpovídají obsahu $poleJmen            
//        if (count( array_diff_key ($generated, $hash)) != 0 ) {      
//            throw new IndexMismatchInKeyAttributtesException('Jména částí klíče v poli $generated  neodpovídají jménům v poli $hash.');
//        }