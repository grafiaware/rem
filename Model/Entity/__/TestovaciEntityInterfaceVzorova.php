<?php

namespace Model\Entity;

use Model\Entity\EntityInterface;

/**
 *
 * @author vlse2610
 */
interface TestovaciEntityInterface_Vzorova extends EntityInterface {
    
       // public function getUidPrimarniKlicZnaky();

        public function getCeleJmeno();

        public function getPrvekVarchar();

        public function getPrvekChar();

        public function getPrvekText();

        public function getPrvekInteger();

        public function getPrvekDate(): \DateTime;

        public function getPrvekDatetime(): \DateTime;

        public function getPrvekTimestamp(): \DateTime;

        public function getPrvekBoolean();
        

        public function setCeleJmeno( string $celeJmeno) :TestovaciEntityInterface;

        public function setPrvekVarchar($prvekVarchar) :TestovaciEntityInterface;

        public function setPrvekChar($prvekChar) :TestovaciEntityInterface;

        public function setPrvekText($prvekText) :TestovaciEntityInterface;

        public function setPrvekInteger($prvekInteger) :TestovaciEntityInterface;

        public function setPrvekDate(\DateTime $prvekDate) :TestovaciEntityInterface;

        public function setPrvekDatetime(\DateTime $prvekDatetime) :TestovaciEntityInterface;

        public function setPrvekTimestamp(\DateTime $prvekTimestamp) :TestovaciEntityInterface;

        public function setPrvekBoolean($prvekBoolean) :TestovaciEntityInterface;
        
        }
