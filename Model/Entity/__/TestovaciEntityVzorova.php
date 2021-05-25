<?php
//namespace Model\Entity;

use Model\Entity\TestovaciEntityInterface;
use Model\Entity\EntityAbstract;


class TestovaciEntity_Vzorova  extends EntityAbstract implements TestovaciEntityInterface_Vzorova {     
    
        /**
         *
         * @var string
         */   
        private $celeJmeno;    

        private $prvekChar;
        private $prvekVarchar;    
        private $prvekText;
        private $prvekInteger;    
        private $prvekBoolean;
        /**
         *
         * @var \DateTime 
         */
        private $prvekDate;
        /**
         *
         * @var \DateTime 
         */
        private $prvekDatetime;
        /**
         *
         * @var \DateTime 
         */
        private $prvekTimestamp;

    //-----------------------------------------------------
    
//        public function getUidPrimarniKlicZnaky() {
//            return $this->uidPrimarniKlicZnaky;
//        }

        public function getCeleJmeno() : string {
            return $this->celeJmeno;
        }

        public function getPrvekVarchar() : string {
            return $this->prvekVarchar;
        }

        public function getPrvekChar(): string {
            return $this->prvekChar;
        }

        public function getPrvekText()  : string{
            return $this->prvekText;
        }

        public function getPrvekInteger()  {
            return $this->prvekInteger;
        }

        public function getPrvekDate(): \DateTime {
            return $this->prvekDate;
        }

        public function getPrvekDatetime(): \DateTime {
            return $this->prvekDatetime;
        }

        public function getPrvekTimestamp(): \DateTime {
            return $this->prvekTimestamp;
        }

        public function getPrvekBoolean() {
            return $this->prvekBoolean;
        }        
        //-----------------------------------

        public function setCeleJmeno( string $celeJmeno) : TestovaciEntityInterface {
           $this->celeJmeno = $celeJmeno;
           return $this;
        }

        public function setPrvekVarchar($prvekVarchar) :TestovaciEntityInterface {
            $this->prvekVarchar = $prvekVarchar;       
            return $this;        
        }

        public function setPrvekChar($prvekChar) :TestovaciEntityInterface {
            $this->prvekChar = $prvekChar;
            return $this;

        }

        public function setPrvekText($prvekText) :TestovaciEntityInterface {
            $this->prvekText = $prvekText;
            return $this;        
        }

        public function setPrvekInteger($prvekInteger) :TestovaciEntityInterface{
            $this->prvekInteger = $prvekInteger;
            return $this;       
        }

        public function setPrvekDate(\DateTime $prvekDate) :TestovaciEntityInterface{
            $this->prvekDate = $prvekDate;
            return $this;        
        }

        public function setPrvekDatetime(\DateTime $prvekDatetime) :TestovaciEntityInterface {
            $this->prvekDatetime = $prvekDatetime;
            return $this;        
        }

        public function setPrvekTimestamp(\DateTime $prvekTimestamp) :TestovaciEntityInterface {
            $this->prvekTimestamp = $prvekTimestamp;
            return $this;      
        }

        public function setPrvekBoolean($prvekBoolean) :TestovaciEntityInterface{
            $this->prvekBoolean = $prvekBoolean;
            return $this;
        }
}