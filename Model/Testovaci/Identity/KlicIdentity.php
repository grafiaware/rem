<?php

namespace Model\Testovaci\Identity;

use Model\Testovaci\Identity\KlicIdentityInterface;
use Model\Entity\Identity\IdentityAbstract;


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
    
    
    
    
    public function getTypIdentity(): string{
        return KlicIdentityInterface::class;
    }
    
    
    public function getKlic(): string {
        return $this->klic;
    }
   
    public function setKlic( string $klic ): void {
        $this->klic = $klic;        
    }
    
    
    
    
}
