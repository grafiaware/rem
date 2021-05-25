<?php
namespace Model\Entity\Hydrator;

use Model\Entity\Hydrator\CeleJmenoGluerInterface;

/**
 * Spojuje a rozpojuje celé jméno. Zatím s oddělovačem '|'.
 */
class CeleJmenoGluer implements CeleJmenoGluerInterface {
    /**
     * @param array $castiJmenaZRowObjectu  asoc. pole,  jmeno vlastnosti rowObjectu => hodnota vlastnosti rowObjectu  
     * @param array $listJmen       seznam casti jmena, urcuje tez poradi casti ( array ['jmeno', 'prijmeni' ] ) 
     * @return string               spojene celé jmeno
     */
    public function stick(  array $castiJmenaZRowObjectu,  array $listJmen) : string {                
        $castiJmenaSerazenePodlePoradiVListJmen = [];
        foreach ( $listJmen as  $v) {            
            $castiJmenaSerazenePodlePoradiVListJmen[] = $castiJmenaZRowObjectu[$v];            
        }            
        $celeJmeno = implode("|", $castiJmenaSerazenePodlePoradiVListJmen);
        return $celeJmeno;       
    }
    
    /**
     * 
     * @param string $celeJmeno vstupujici retezec ( spojene části jména s oddelovacem '|')
     * @param array $listJmen   seznam casti jmena, v ocekavanem poradi ( např.array ['jmeno', 'prijmeni' ] )
     * @return array            asoc. pole casti jmen, tj. jmeno casti => hodnota casti
     */
    public function unstick( string $celeJmeno, array $listJmen ) : array {        
        //array : jmeno vlastn.rowObjectu => data vlastn.rowObjectu                
        $casti = [];
        $castiJmena = explode("|", $celeJmeno);          
        $i=0;
        foreach ( $listJmen as $value ) {            
            $casti[$value] = $castiJmena[$i]; 
            $i = $i+1;
        }                        
        return $casti;
    } 
}


       
//             //pole jmen vlastnosti rowobjectu se zachovanym poradim
//            foreach ($list as $itemIndex => $item) {
//                $propertyName = $this->attributeNameHydrator->hydrate($item); // vytvoreni jmena vlastnosti row objectu
//                $listArray [$itemIndex] =  $rowObject->$propertyName;               
//            }                   

//+++
//varianta b       $celeJmeno = $this->attributeNameHydrator->hydrate($item["0"])."|".$this->attributeNameHydrator->hydrate($item["prijmeni"]);           
