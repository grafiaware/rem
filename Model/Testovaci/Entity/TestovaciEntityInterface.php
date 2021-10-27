<?php

namespace Model\Testovaci\Entity;

use Model\Entity\EntityInterface;

/**
 *
 * @author vlse2610
 */
interface TestovaciEntityInterface extends EntityInterface{
    
    
        public function getAssociatedCarrotEntity(): TestovaciAssociatedCarrotEntityInterface ;
       
        public function getCeleJmeno(): string; 
        public function getPrvekVarchar(): string; 
        public function getPrvekDatetime(): \DateTime; 
       
                
        public function setAssociatedCarrotEntity(TestovaciAssociatedCarrotEntityInterface $associatedCarrotEntity) ;

        public function setCeleJmeno(string $celeJmeno):TestovaciEntityInterface ;
        public function setPrvekVarchar(string $prvekVarchar) : TestovaciEntityInterface ;
        public function setPrvekDatetime(\DateTime $prvekDatetime): TestovaciEntityInterface ;

              
    
}
