<?php

namespace Model\Testovaci\Entity;

use Model\Entity\EntityAbstract;

use Model\Testovaci\Entity\CarrotEntityInterface;
use Model\Testovaci\Identity\CarrotIdentityInterface;
use Model\Testovaci\Identity\RabbitIdentityInterface;



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
     */
    public function __construct(  
            CarrotIdentityInterface $carrotIdentity,
            RabbitIdentityInterface $rabbitIdentityFk ) 
    {        
       //parent::__construct($identity);     
        
        $this->carrotIdentity = $carrotIdentity;
        $this->rabbitIdentityFk = $rabbitIdentityFk;
        
        $this->identities[ get_class($carrotIdentity)] = $carrotIdentity;
        $this->identities[ get_class($rabbitIdentityFk)] = $rabbitIdentityFk;                                
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
