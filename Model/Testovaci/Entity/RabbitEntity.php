<?php

namespace Model\Testovaci\Entity;

use Model\Entity\EntityAbstract;

use Model\Testovaci\Entity\RabbitEntityInterface;
use Model\Testovaci\Entity\HoleEntityInterface;




class RabbitEntity extends EntityAbstract implements RabbitEntityInterface {         
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
         * @var array
         */      
        private $associatedCarrotEntities;
        
        /**
         *
         * @var HoleEntityInterface 
         */
        private $associatedHoleEntity;
        
                    
        
       
        
        public function getAssociatedCarrotEntities(): array /*\Traversable */{
            return $this->associatedCarrotEntities;
        }       
        public function setAssociatedCarrotEntities( array  /*\Traversable*/  $associatedCarrotEntities ) : void {
            $this->associatedCarrotEntities = $associatedCarrotEntities;
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
