<?php

namespace Model\Testovaci\Entity;

use Model\Entity\EntityAbstract;

use Model\Testovaci\Entity\RabbitEntityInterface;
use Model\Testovaci\Entity\HoleEntityInterface;

use Model\Testovaci\Identity\RabbitIdentityInterface;
use Model\Testovaci\Identity\KlicIdentityInterface;



        //class TestovaciEntity extends EntityAbstract implements TestovaciEntityInterface {    
class RabbitEntity extends EntityAbstract implements RabbitEntityInterface {         
        // vsechny identity teto entity tady a v Abstract nejsou
    
        /**
         *
         * @var RabbitIdentityInterface 
         */     
        private $rabbitIdentity;
               
        /**
         *
         * @var KlicIdentityInterface 
         */             
        private $klicIdentity;
 
    
//--------------------------------------------    
        /**
         * @var string
         */   
        private $celeJmeno;          
        /**
         *
         * @var string
         */
        private $prvekVarchar;           
        /**
         *
         * @var \DateTime 
         */
        private $prvekDatetime;
        
//---------------------------------------------        
        /**
         * 
         * @var \Traversable
         */      
        private $associatedCarrotEntities;
        
        /**
         *
         * @var HoleEntityInterface 
         */
        private $associatedHoleEntity;
        
        
               
        
        /**
         * 
         * @param RabbitIdentityInterface $rabbitIdentity
         * @param KlicIdentityInterface $klicIdentity
         */
        public function __construct( RabbitIdentityInterface $rabbitIdentity, 
                                     KlicIdentityInterface $klicIdentity                
                                   ) {         
            $this->rabbitIdentity = $rabbitIdentity;
            $this->klicIdentity   = $klicIdentity;            
        }  
        
        
        public function getRabbitIdentity():  RabbitIdentityInterface {
            return $this->rabbitIdentity;
        }       
        public function getKlicIdentity():  KlicIdentityInterface {
            return $this->klicIdentity;
        }     
        // sety identit nejsou - identity v konstruktoru      
        
       
        
        public function getAssociatedCarrotEntities():  \Traversable {
            return $this->associatedCarrotEntities;
        }       
        public function setAssociatedCarrotEntities( \Traversable $associatedCarrotEntities ) : void {
        }
        
        
        
        
        public function getAssociatedHoleEntity(): ?HoleEntityInterface {
            return $this->associatedHoleEntity;
        }
        public function setAssociatedHoleEntity( HoleEntityInterface $associatedHoleEntity) : void {
            $this->associatedHoleEntity = $associatedHoleEntity;
        }

        
        
        public function getCeleJmeno(): string {
            return $this->celeJmeno;
        }
        public function getPrvekVarchar(): string {
            return $this->prvekVarchar;
        }              
        public function getPrvekDatetime(): \DateTime {            
            return $this->prvekDatetime;
        }
       
                       
        public function setCeleJmeno(string $celeJmeno) : void {
            $this->celeJmeno = $celeJmeno;
        }
        public function setPrvekVarchar(string $prvekVarchar) :void {
            $this->prvekVarchar = $prvekVarchar;
        }        
        public function setPrvekDatetime(\DateTime $prvekDatetime) : void {
            $this->prvekDatetime = $prvekDatetime;
        }

              
        
}
