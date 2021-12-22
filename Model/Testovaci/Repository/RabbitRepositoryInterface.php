<?php

namespace Model\Testovaci\Repository;

use Model\Testovaci\Entity\RabbitEntityInterface;
use Model\Testovaci\Identity\RabbitIdentityInterface;
use Model\Testovaci\Identity\CarrotIdentityInterface;
use Model\Testovaci\Identity\HoleIdentityInterface;
use Model\Testovaci\Identity\KlicIdentityInterface;

use Model\Repository\RepositoryInterface;

/**
 *
 * @author vlse2610
 */
interface RabbitRepositoryInterface extends RepositoryInterface {
    
//    //nebude potreba......???NENENENEN, pac vpbehu je jine jmeno
//    const IDENTITIES_NAMES = [ 'RabbitIdentityInterface',
//                                 'KlicIdentityInterface'
//            ];
    
    
    
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
    public function getByRabbit ( RabbitIdentityInterface $identityRabbit  ) : ?RabbitEntityInterface ;
    
    public function getByKlic ( KlicIdentityInterface  $identityKlic  ) : ?RabbitEntityInterface ;
          
    
    
    
    //??????????????????? v rabbit neni sloupecek s cizim klicem
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
