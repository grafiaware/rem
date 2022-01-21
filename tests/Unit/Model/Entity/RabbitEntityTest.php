<?php
namespace Test\EntityTest;

use PHPUnit\Framework\TestCase;

use Model\Entity\Identity\IdentityInterface;
use Model\Entity\Identity\IdentityAbstract;
use Model\Entity\EntityInterface;
use Model\Entity\EntityAbstract;

use Model\Testovaci\Entity\RabbitEntity;
use \Model\Testovaci\Entity\Enum\IdentityTypeEnum;


interface KlicIdentityInterfaceMock extends IdentityInterface {    
}
interface RabbitIdentityInterfaceMock extends IdentityInterface {
}
class RabbitIdentityNamesEnumMock extends IdentityTypeEnum{    
    const RABBITIDENTITYINTERFACEMOCK = RabbitIdentityInterfaceMock::class;
    const KLICIDENTITYINTERFACEMOCK = KlicIdentityInterfaceMock::class;
}
class RabbitIdentityMock extends IdentityAbstract implements RabbitIdentityInterfaceMock {   
    public function getTypeIdentity(): string {
        return RabbitIdentityNamesEnumMock::RABBITIDENTITYINTERFACEMOCK;
    }
}


interface CarrotIdentityInterfaceMock extends IdentityInterface {
}   
class CarrotIdentityNamesEnumMock extends IdentityTypeEnum{    
    const CARROTIDENTITYINTERFACEMOCK = CarrotIdentityInterfaceMock::class;
}
class CarrotIdentityMock extends IdentityAbstract implements CarrotIdentityInterfaceMock {       
    public function getTypeIdentity(): string {
        return CarrotIdentityNamesEnumMock::CARROTIDENTITYINTERFACEMOCK;
    }
}


interface HoleIdentityInterfaceMock extends IdentityInterface {
}    
class HoleIdentityNamesEnumMock extends IdentityTypeEnum{    
    const HOLEIDENTITYINTERFACEMOCK = HoleIdentityInterfaceMock::class;
}
class HoleIdentityMock extends IdentityAbstract implements HoleIdentityInterfaceMock {       
    public function getTypeIdentity(): string {
        return HoleIdentityNamesEnumMock::HOLEIDENTITYINTERFACEMOCK;
    }
}
//-----------------------------------


interface CarrotEntityInterfaceMock extends EntityInterface {    
} 
class CarrotEntityMock extends EntityAbstract implements CarrotEntityInterfaceMock{    
}
interface HoleEntityInterfaceMock extends EntityInterface {     
} 
class HoleEntityMock extends EntityAbstract implements HoleEntityInterfaceMock{    
}


//----------------------------------------------------------------------------------------------------
/**
 * Description of TestovaciEntityTest
 *
 * @author vlse2610
 */
class RabbitEntityTest extends TestCase {
    /**
     *
     * @var \ArrayObject
     */
    private $rabbitIdentities;
    /**
     *
     * @var \ArrayObject
     */
    private $holeIdentities;
    /**
     *
     * @var \ArrayObject
     */
    private $carrotIdentities;    
    
    
    private $holeEntity;
    
    private $carrotEntity; 
    /**
     *
     * @var \ArrayObject
     */
    private $carrotEntities; 
    
    
    
    
    
    public function setUp(): void {  
//        $rabbitIdentityEnum = new RabbitIdentityNamesEnumMock();
//        $this->nameRabbitIdentityInterfaceMock = $rabbitIdentityEnum(RabbitIdentityNamesEnumMock::RABBITIDENTITYINTERFACEMOCK);
//        $this->nameKlicIdentityInterfaceMock = $rabbitIdentityEnum(RabbitIdentityNamesEnumMock::KLICIDENTITYINTERFACEMOCK);
           
//        RabbitIdentityNamesEnumMock::RABBITIDENTITYINTERFACEMOCK
//        RabbitIdentityNamesEnumMock::KLICIDENTITYINTERFACEMOCK        
        //---------------------------------------------------
        
        
        $rabIdentity[RabbitIdentityNamesEnumMock::RABBITIDENTITYINTERFACEMOCK ]  = new RabbitIdentityMock ( ); 
        $this->rabbitIdentities = new \ArrayObject( $rabIdentity );
        
        $holIdentity[ HoleIdentityNamesEnumMock::HOLEIDENTITYINTERFACEMOCK ]  = new HoleIdentityMock ( ); 
        $this->holeIdentities = new \ArrayObject( $holIdentity );
        
        $carIdentity[CarrotIdentityNamesEnumMock::CARROTIDENTITYINTERFACEMOCK ]  = new CarrotIdentityMock ( ); 
        $this->carrotIdentities = new \ArrayObject( $carIdentity );
        
        
        $this->carrotEntity = new CarrotEntityMock ( $this->carrotIdentities );
        $carrotEnt [] =  $this->carrotEntity;
        
        $this->carrotEntities  = new \ArrayObject(  $carrotEnt );
        $this->holeEntity = new HoleEntityMock ( $this->holeIdentities );
        
        
    }
    
    
    public function testConstruct(  ) : void {           
        $this->assertInstanceOf( RabbitEntity::class, new RabbitEntity ( $this->rabbitIdentities ));                
    }
    
    
    public function testGetIdentity() {
        $entity = new RabbitEntity ( $this->rabbitIdentities );       
        $this->assertInstanceOf( RabbitIdentityInterfaceMock::class, $entity->getIdentity( RabbitIdentityNamesEnumMock::RABBITIDENTITYINTERFACEMOCK ));        
    }
    
    public function testGetIdentities() {
        $entity = new RabbitEntity ( $this->rabbitIdentities );       
        $identities = $entity->getIdentities();
        
        foreach ($this->rabbitIdentities as $name=>$value) {
            foreach ($identities as $name1=>$value1) {
                $this->assertEquals( $name, $name1);
                $this->assertEquals( $value, $value1);
            }
        }     
    }
    
    
    public function testGetersSeters() {
        $entity = new RabbitEntity ( $this->rabbitIdentities ); 
        
        $entity->setAssociatedCarrotEntities( $this->carrotEntities ) ;
        $carrotEntities = $entity->getAssociatedCarrotEntities();
        $this->assertInstanceOf ( \ArrayObject::class,  $carrotEntities );
                
       // $entity->setAssociatedHoleEntity($associatedHoleEntity)
       // $entity->setCeleJmeno($celeJmeno)
        //$entity->setPrvekDatetime($prvekDatetime)
        //$entity->setPrvekVarchar($prvekVarchar)
        //$entity->setPersisted()
        //$entity->setUnpersisted();
        //$entity->lock();
        //$entity->isLocked()
        
        
        

        
        
        
        
    }
    
    
    
    
}



//--------------------------------------------------------------------------------


//interface TestovaciEntityInterfaceMock extends EntityInterface {    
//        public function getCeleJmeno();
//        public function getPrvekVarchar();
//        public function getPrvekChar();
//        public function getPrvekText();
//        public function getPrvekInteger();
//        public function getPrvekDate(): \DateTime;
//        public function getPrvekDatetime(): \DateTime;
//        public function getPrvekTimestamp(): \DateTime;
//        public function getPrvekBoolean();        
//        public function setCeleJmeno( string $celeJmeno) :TestovaciEntityInterfaceMock;
//        public function setPrvekVarchar($prvekVarchar) :TestovaciEntityInterfaceMock;
//        public function setPrvekChar($prvekChar) :TestovaciEntityInterfaceMock;
//        public function setPrvekText($prvekText) :TestovaciEntityInterfaceMock;
//        public function setPrvekInteger($prvekInteger) :TestovaciEntityInterfaceMock;
//        public function setPrvekDate(\DateTime $prvekDate) :TestovaciEntityInterfaceMock;
//        public function setPrvekDatetime(\DateTime $prvekDatetime) :TestovaciEntityInterfaceMock;
//        public function setPrvekTimestamp(\DateTime $prvekTimestamp) :TestovaciEntityInterfaceMock;
//        public function setPrvekBoolean($prvekBoolean) :TestovaciEntityInterfaceMock;        
//}
//class TestovaciEntityMock extends EntityAbstract implements TestovaciEntityInterfaceMock {      
//        /**
//         *
//         * @var string
//         */   
//        private $celeJmeno;    
//
//        private $prvekChar;
//        private $prvekVarchar;    
//        private $prvekText;
//        private $prvekInteger;    
//        private $prvekBoolean;
//        /**
//         *
//         * @var \DateTime 
//         */
//        private $prvekDate;
//        /**
//         *
//         * @var \DateTime 
//         */
//        private $prvekDatetime;
//        /**
//         *
//         * @var \DateTime 
//         */
//        private $prvekTimestamp;
//
//    //----------------------------------------------------- 
//
//        public function getCeleJmeno() : string {
//            return $this->celeJmeno;
//        }
//
//        public function getPrvekVarchar() : string {
//            return $this->prvekVarchar;
//        }
//
//        public function getPrvekChar(): string {
//            return $this->prvekChar;
//        }
//
//        public function getPrvekText()  : string{
//            return $this->prvekText;
//        }
//
//        public function getPrvekInteger()  {
//            return $this->prvekInteger;
//        }
//
//        public function getPrvekDate(): \DateTime {
//            return $this->prvekDate;
//        }
//
//        public function getPrvekDatetime(): \DateTime {
//            return $this->prvekDatetime;
//        }
//
//        public function getPrvekTimestamp(): \DateTime {
//            return $this->prvekTimestamp;
//        }
//
//        public function getPrvekBoolean() {
//            return $this->prvekBoolean;
//        }        
//        //-----------------------------------
//
//        public function setCeleJmeno( string $celeJmeno) : TestovaciEntityInterfaceMock {
//           $this->celeJmeno = $celeJmeno;
//           return $this;
//        }
//
//        public function setPrvekVarchar($prvekVarchar) :TestovaciEntityInterfaceMock {
//            $this->prvekVarchar = $prvekVarchar;       
//            return $this;        
//        }
//
//        public function setPrvekChar($prvekChar) :TestovaciEntityInterfaceMock {
//            $this->prvekChar = $prvekChar;
//            return $this;
//
//        }
//
//        public function setPrvekText($prvekText) :TestovaciEntityInterfaceMock {
//            $this->prvekText = $prvekText;
//            return $this;        
//        }
//
//        public function setPrvekInteger($prvekInteger) :TestovaciEntityInterfaceMock {
//            $this->prvekInteger = $prvekInteger;
//            return $this;       
//        }
//
//        public function setPrvekDate(\DateTime $prvekDate) :TestovaciEntityInterfaceMock {
//            $this->prvekDate = $prvekDate;
//            return $this;        
//        }
//
//        public function setPrvekDatetime(\DateTime $prvekDatetime) :TestovaciEntityInterfaceMock {
//            $this->prvekDatetime = $prvekDatetime;
//            return $this;        
//        }
//
//        public function setPrvekTimestamp(\DateTime $prvekTimestamp) :TestovaciEntityInterfaceMock {
//            $this->prvekTimestamp = $prvekTimestamp;
//            return $this;      
//        }
//
//        public function setPrvekBoolean($prvekBoolean) :TestovaciEntityInterfaceMock {
//            $this->prvekBoolean = $prvekBoolean;
//            return $this;
//        }
//}
//
