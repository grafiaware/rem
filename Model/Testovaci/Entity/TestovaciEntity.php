<?php

namespace Model\Testovaci\Entity;

use Model\Entity\EntityAbstract;
use Model\Testovaci\Entity\TestovaciEntityInterface;

use Model\Testovaci\Identity\TestovaciIdentityInterface;

/**
 * Description of TestovaciEntity
 *
 * @author vlse2610
 */
class TestovaciEntity extends EntityAbstract implements TestovaciEntityInterface {
       //V EntityAbstract JE Identity
    
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
         * @param TestovaciIdentityInterface $identity
         */
        public function __construct( TestovaciIdentityInterface $identity ) {
            parent::__construct($identity);
         
            
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
