<?php

namespace Model\Testovaci\Identity;

/**
 *
 * @author vlse2610
 */
interface KlicIdentityInterface {
    
    public function getKlic(): string ;
   
    public function setKlic( string $klic ): void ;
    
    
}
