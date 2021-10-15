<?php

namespace Model\Testovaci\Repository;

use Model\Testovaci\Entity\TestovaciEntityInterface;
use Model\Testovaci\Identity\TestovaciIdentityInterface;
use Model\Testovaci\Identity\TestovaciReferenceIdentityInterface;

use Model\Repository\RepositoryInterface;

/**
 *
 * @author vlse2610
 */
interface TestovaciRepositoryInterface extends RepositoryInterface {
    
    /**
     * Přidá  TestovaciEntity do TestovaciRepository.
     * 
     * @param TestovaciEntityInterface $entity
     * @return void
     */
    public function add ( TestovaciEntityInterface $entity ) : void ;
    
    
    /**
     * Vrací  TestovaciEntity z TestovaciRepository  podle TestovaciIdentity, nebo vraci null.
     * 
     * @param TestovaciIdentityInterface $identity
     * @return TestovaciEntityInterface|null
     */
    public function get ( TestovaciIdentityInterface $identity ) : ?TestovaciEntityInterface ;
    
    
    
    public function getByReference (TestovaciReferenceIdentityInterface $identityReference ) : ?TestovaciEntityInterface ;
    
    
    
    /**
     * Odstraní TestovaciEntity z TestovaciRepository.
     * 
     * @param TestovaciEntityInterface $entity
     * @return void
     */    
    public function remove ( TestovaciEntityInterface $entity ) : void  ;
    
    
    
    
    
    /** 
     * je v abstractu (nepouzivat) a TestovaciRepository
     */
    //public function createEntity() : TestovaciEntityInterface;
          
  
   
            
}
