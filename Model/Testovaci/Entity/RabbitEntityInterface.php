<?php

namespace Model\Testovaci\Entity;

use Model\Entity\EntityInterface;

/**
 *
 * @author vlse2610
 */
interface RabbitEntityInterface extends EntityInterface{
    
    
        public function getRabbitIdentity():  RabbitIdentityInterface ;
        public function getKlicIdentity():  KlicIdentityInterface ;
        
    
        public function getAssociatedCarrotEntities(): \Traversable ;
        public function setAssociatedCarrotEntities( \Traversable $associatedCarrotEntities ) : void ;
               
        public function getAssociatedHoleEntity(): ?HoleEntityInterface ;
        public function setAssociatedHoleEntity(HoleEntityInterface $associatedHoleEntity) : void ;
        
        
        public function getCeleJmeno(): string; 
        public function getPrvekVarchar(): string; 
        public function getPrvekDatetime(): \DateTime; 
       
        public function setCeleJmeno(string $celeJmeno): void ;
        public function setPrvekVarchar(string $prvekVarchar) : void ;
        public function setPrvekDatetime(\DateTime $prvekDatetime): void ;

              
    
}
