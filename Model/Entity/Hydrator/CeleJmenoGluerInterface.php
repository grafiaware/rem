<?php
namespace Model\Entity\Hydrator;

/**
 *
 * @author vlse2610
 */
interface CeleJmenoGluerInterface {
   
    public function stick( array $castiJmena,  array $listJmen):  string;       
    
    public function unstick(  string $celeJmeno, array $listJmen ) : array; 
}
