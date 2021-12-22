<?php

namespace Model\Testovaci\Entity;

use Model\Entity\EntityInterface;
use Model\Testovaci\Identity\RabbitIdentityInterface;
/**
 *
 * @author vlse2610
 */
interface RabbitEntityInterface extends EntityInterface{
    
        
//        const IDENTITIES_NAMES = [ 'RabbitIdentityInterface',
//                                   'KlicIdentityInterface'
//            ];
//       
       
    
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
