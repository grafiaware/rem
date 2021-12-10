<?php

namespace Model\Testovaci\Entity;

use Model\Entity\EntityAbstract;
use Model\Testovaci\Entity\TestovaciEntityInterface;
use Model\Testovaci\Entity\TestovaciAssociatedCarrotEntityInterface;
use Model\Testovaci\Entity\TestovaciAssociatedHoleEntityInterface;

use Model\Testovaci\Identity\TestovaciIdentityInterface;

/**
 * Description of TestovaciEntity
 *
 * @author vlse2610
 */
class TestovaciEntity extends EntityAbstract implements TestovaciEntityInterface {
       //V EntityAbstract JE Identity
    
        // a nebo jsou vsechny identity teto entity tady a v Abstract nejsou
    
    
    
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
        
        /**
         * 
         * @var TestovaciAssociatedCarrotEntityInterface
         */      
        private $associatedCarrotEntity;
        
        /**
         *
         * @var TestovaciAssociatedHoleEntityInterface 
         */
        private $associatedHoleEntity;
        
        
        
        /**
         * 
         * @param TestovaciIdentityInterface $identity
         */
        public function __construct( TestovaciIdentityInterface $identity /* ++ dalsi identity*/) {
            parent::__construct($identity);                     
        }  
        
       
        public function getAssociatedCarrotEntity(): TestovaciAssociatedCarrotEntityInterface {
            return $this->associatedCarrotEntity;
        }       
        public function setAssociatedCarrotEntity(TestovaciAssociatedCarrotEntityInterface $associatedCarrotEntity) :TestovaciEntityInterface {
            $this->associatedCarrotEntity = $associatedCarrotEntity;
            return $this;
        }
        public function getAssociatedHoleEntity(): TestovaciAssociatedHoleEntityInterface {
            return $this->associatedHoleEntity;
        }
        public function setAssociatedHoleEntity(TestovaciAssociatedHoleEntityInterface $associatedHoleEntity) :TestovaciEntityInterface {
            $this->associatedHoleEntity = $associatedHoleEntity;
            return $this;
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

       
        
        
        
        public function setCeleJmeno(string $celeJmeno) :TestovaciEntityInterface {
            $this->celeJmeno = $celeJmeno;
            return $this;
        }
        public function setPrvekVarchar(string $prvekVarchar) :TestovaciEntityInterface {
            $this->prvekVarchar = $prvekVarchar;
            return $this;
        }        
        public function setPrvekDatetime(\DateTime $prvekDatetime) :TestovaciEntityInterface {
            $this->prvekDatetime = $prvekDatetime;
            return $this;
        }

              
        
}
