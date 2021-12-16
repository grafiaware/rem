<?php

namespace Model\Testovaci\Repository;

use Model\Testovaci\Entity\RabbitEntityInterface;
use Model\Testovaci\Identity\RabbitIdentityInterface;
use Model\Testovaci\Identity\CarrotIdentityInterface;
use Model\Testovaci\Identity\HoleIdentityInterface;

use Model\Repository\RepositoryInterface;

/**
 *
 * @author vlse2610
 */
interface RabbitRepositoryInterface extends RepositoryInterface {
    
    /**
     * Přidá  RabbitEntity do RabbitRepository.
     * 
     * @param RabbitEntityInterface $entity
     * @return void
     */
    public function add ( RabbitEntityInterface $entity ) : void ;
    
    
    /**
     * Vrací  RabbitEntity z RabbitRepository  podle RabbitIdentity, nebo vraci null    
     * 
     * @param RabbitIdentityInterface $identity
     * @return RabbitEntityInterface|null
     */
    public function get ( /* $identityHash*/  RabbitIdentityInterface $identity  ) : ?RabbitEntityInterface ;
          
    
    
    
    
    public function getByReferenceCarrot( CarrotIdentityInterface $identity ) : ?RabbitEntityInterface ;
    
    
    public function getByReferenceHole( HoleIdentityInterface $identity ) : ?RabbitEntityInterface ;
    
    
    
    /**
     * Odstraní RabbitEntity z RabbitRepository.
     * 
     * @param RabbitEntityInterface $entity
     * @return void
     */    
    public function remove ( RabbitEntityInterface $entity ) : void  ;
    
    
    
    
    
    /** 
     * je v abstractu (nepouzivat) a TestovaciRepository
     */
    //public function createEntity() : TestovaciEntityInterface;
          
  
   
            
}
