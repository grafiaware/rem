<?php

namespace Model\Testovaci\Entity;

use Model\Entity\EntityAbstract;

use Model\Testovaci\Entity\HoleEntityInterface;
use Model\Testovaci\Identity\HoleIdentityInterface;
use Model\Testovaci\Identity\RabbitIdentityInterface;

/**
 * Description 
 *
 * @author vlse2610
 */
class HoleEntity extends EntityAbstract implements HoleEntityInterface{
    /**
     *
     * @var integer 
     */
    private $hloubka;
    /**
     *
     * @var integer 
     */
    private $adresa;
        
    
    /**
     *
     * @var HoleIdentityInterface 
     */    
    private $holeIdentity;        
    /**
     *
     * @var RabbitIdentityInterface
     */
    private $rabbitIdentityFk;
    
    
        
    /**
     * 
     * @param HoleIdentityInterface $holeIdentity
     * @param RabbitIdentityInterface $rabbitIdentityFk
     */
    public function __construct(  
                        HoleIdentityInterface $holeIdentity,
                        RabbitIdentityInterface $rabbitIdentityFk) 
    {              
        $this->holeIdentity = $holeIdentity;
        $this->rabbitIdentityFk = $rabbitIdentityFk;
        
//        $this->identities[ get_class($holeIdentity)] = $holeIdentity;
//        $this->identities[ get_class($rabbitIdentityFk)] = $rabbitIdentityFk;
        
        $this->identities[ HoleIdentityInterface::class ] = $holeIdentity;
        $this->identities[ RabbitIdentityInterface::class ] = $rabbitIdentityFk;   
        
    }  
         
    
    
    
    
   
    
    public function getHoleIdentity() : HoleIdentityInterface  {
        return $this->holeIdentity;
    }
    public function getRabbitIdentityFk() : RabbitIdentityInterface {
        return $this->rabbitIdentityFk;
    }
    
    
    public function getHloubka(): integer {
        return $this->hloubka;
    }

    public function getAdresa(): integer {
        return $this->adresa;
    }

    public function setHloubka(integer $hloubka) : void {
        $this->hloubka = $hloubka;
    }

    public function setAdresa(integer $adresa) : void {
        $this->adresa = $adresa;
    }




}
