<?php

namespace Model\Testovaci\Identity;

use Model\Testovaci\Identity\KlicIdentityInterface;

/**
 * Description of KlicIdentity
 *
 * @author vlse2610
 */
class KlicIdentity extends IdentityAbstract implements KlicIdentityInterface  {
    /**
     *
     * @var string
     */
    private $klic;
    
    
    public function __construct( ) {

    }
    
    public function getKlic(): string {
        return $this->klic;
    }
   
    public function setKlic( string $klic ): void {
        $this->klic = $klic;        
    }
    
    
    
    
}
