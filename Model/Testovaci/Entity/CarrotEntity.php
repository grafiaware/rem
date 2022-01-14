<?php

namespace Model\Testovaci\Entity;

use Model\Entity\EntityAbstract;

use Model\Testovaci\Entity\CarrotEntityInterface;
use Model\Testovaci\Identity\CarrotIdentityInterface;
use Model\Testovaci\Identity\RabbitIdentityInterface;

use Model\Testovaci\Entity\Enum\CarrotIdentityNamesEnum;


/**
 * Description 
 *
 * @author vlse2610
 */
class CarrotEntity  extends EntityAbstract implements CarrotEntityInterface{
    /**
     *
     * @var integer
     */ 
    private $prumer;
    
    
   
    
    /**
     *
     * @var CarrotIdentityInterface 
     */    
    private $carrotIdentity;                     
    /**
     *
     * @var RabbitIdentityInterface
     */
    private $rabbitIdentityFk;       
    
            
    
    /**
     * 
     * @param CarrotIdentityInterface $carrotIdentity
     * @param RabbitIdentityInterface $rabbitIdentityFk
     * @param CarrotIdentityNamesEnum $identityNames
     */
    public function __construct(  
            CarrotIdentityInterface $carrotIdentity,
            RabbitIdentityInterface $rabbitIdentityFk, 
            
            CarrotIdentityNamesEnum $identityNames            
            ){        
       
        $this->carrotIdentity = $carrotIdentity;
        $this->rabbitIdentityFk = $rabbitIdentityFk;
        
        $this->identityNames = $identityNames;                 
        
        $this->identities[ CarrotIdentityInterface::class ] = $carrotIdentity;
        $this->identities[ RabbitIdentityInterface::class ] = $rabbitIdentityFk; 
        
    }  
                     
    
    public function getCarrotIdentity() : CarrotIdentityInterface  {
        return $this->carrotIdentity;
    }
    public function getRabbitIdentityFk() :  RabbitIdentityInterface {
        return $this->rabbitIdentityFk;
    }
     
    
    
    public function getPrumer() : integer {
        return $this->prumer;
    }
    public function setPrumer($prumer): void  {
        $this->prumer = $prumer;
    }


     
}
