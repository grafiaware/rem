<?php

namespace Model\Testovaci\Entity;

use Model\Testovaci\Entity\TestovaciAssociatedCarrotEntityInterface;
use Model\Entity\EntityAbstract;
use Model\Testovaci\Identity\TestovaciCarrotIdentityInterface;
use Model\Testovaci\Entity\TestovaciEntityInterface;



/**
 * Description of TestovaciAssociatedCarror
 *
 * @author vlse2610
 */
class TestovaciAssociatedCarrotEntity  extends EntityAbstract implements TestovaciAssociatedCarrotEntityInterface{
    /**
     *
     * @var integer
     */ 
    private $prumer;
    
    
    /**
     *
     * @var TestovaciEntityInterface
     */
    private $identityKralikaFk;
    
    
    
    
    
    
    
    /**
     * 
     * @param  $identity
     */
    public function __construct(  //array $identities )
            TestovaciCarrotIdentityInterface $identity, TestovaciEntityInterface $identityKralika ) 
            {
        
        parent::__construct($identity);        
        $this->identityKralikaFk = $identityKralika;
        
        $this->identities[ get_class($identity)] = $identity;
        $this->identities[ get_class($identityKralika)] = $identityKralika;
                        
        
    }  
         
    
    
    public function getIdentityKralikaFk() : IdentityInterface {
        return $this->identityKralikaFk;
    }
    
    
    
    
    public function getPrumer() : integer {
        return $this->prumer;
    }

    public function setPrumer($prumer): TestovaciAssociatedCarrotEntityInterface  {
        $this->prumer = $prumer;
        return $this;
    }


     
}
