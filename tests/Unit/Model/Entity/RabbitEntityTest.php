<?php
namespace Test\EntityTest;

use PHPUnit\Framework\TestCase;

use Model\Entity\Identity\IdentityInterface;
use Model\Entity\Identity\IdentityAbstract;
use Model\Entity\EntityAbstract;
use Model\Entity\Identities;

use Model\Testovaci\Entity\RabbitEntity;
use Model\Entity\Enum\IdentityTypeEnum;
use Model\Testovaci\Entity\CarrotEntityInterface;
use Model\Testovaci\Entity\HoleEntityInterface;


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

class KlicIdentityMock extends IdentityAbstract implements KlicIdentityInterfaceMock {   
    public function getTypeIdentity(): string {
        return RabbitIdentityNamesEnumMock::KLICIDENTITYINTERFACEMOCK;
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
//----------  entity  -----------------

//interface CarrotEntityInterfaceMock extends EntityInterface {    
//} 
class CarrotEntityMock extends EntityAbstract implements CarrotEntityInterface{  
     /**
     *
     * @var integer
     */ 
    private $prumer;         
    
     public function getPrumer() : int {
        return $this->prumer;
    }
    public function setPrumer(int $prumer): void  {
        $this->prumer = $prumer;
    }
}

//interface HoleEntityInterfaceMock extends EntityInterface {     
//} 
class HoleEntityMock extends EntityAbstract implements HoleEntityInterface{  
     /**
     *
     * @var int
     */
    private $hloubka;
    /**
     *
     * @var int
     */
    private $adresa;
     
    public function getHloubka(): int {
        return $this->hloubka;
    }

    public function getAdresa(): int {
        return $this->adresa;
    }

    public function setHloubka(int $hloubka) : void {
        $this->hloubka = $hloubka;
    }

    public function setAdresa(int $adresa) : void {
        $this->adresa = $adresa;
    }

}


//----------------------------------------------------------------------------------------------------
/**
 * Description of RabbitEntityTest
 *
 * @author vlse2610
 */
class RabbitEntityTest extends TestCase {
    /**
     *
     * @var Identities
     */
    private $rabbitIdentities;
           
    
    /**
     *
     * @var Identities
     */
    private $holeIdentities;
    /**
     *
     * @var Identities
     */
    private $carrotIdentities;    
    //--------------
    
    private $holeEntity;
    
    private $carrotEntity1;          
    /**
     *
     * @var array
     */
    private $carrotEntities; 
    
    
    
    
    
    public function setUp(): void {          
//      krasny priklad - takto  lze naplnit taky
//      $this->rabbitIdentities = new Identities();
//      $this->rabbitIdentities[RabbitIdentityNamesEnumMock::RABBITIDENTITYINTERFACEMOCK ]  = new RabbitIdentityMock ( );
       

        //identity do construktoru
        $rabbitIdentities[RabbitIdentityNamesEnumMock::RABBITIDENTITYINTERFACEMOCK ]  = new RabbitIdentityMock ( );
        $rabbitIdentities[RabbitIdentityNamesEnumMock::KLICIDENTITYINTERFACEMOCK ]  = new KlicIdentityMock ( );
        $this->rabbitIdentities = new Identities(new RabbitIdentityNamesEnumMock(), $rabbitIdentities );        
//        $this->rabbitIdentityEnum = new RabbitIdentityNamesEnumMock();
        
        
        
        
        $holeIdentities[HoleIdentityNamesEnumMock::HOLEIDENTITYINTERFACEMOCK ]  = new HoleIdentityMock ( ); 
        $this->holeIdentities = new Identities(new HoleIdentityNamesEnumMock(), $holeIdentities);
//         $holeIdentityEnum = new HoleIdentityNamesEnumMock();
        $this->holeEntity = new HoleEntityMock ($this->holeIdentities);
        
        $carrotIdentities[CarrotIdentityNamesEnumMock::CARROTIDENTITYINTERFACEMOCK ]  = new CarrotIdentityMock ( ); 
        $this->carrotIdentities = new Identities(new CarrotIdentityNamesEnumMock(), $carrotIdentities );                
//        $carrotIdentityEnum = new CarrotIdentityNamesEnumMock();
        $this->carrotEntity1 = new CarrotEntityMock ($this->carrotIdentities);        
        $carrotEntities[] =  $this->carrotEntity1;        
        $this->carrotEntities  = $carrotEntities ;
        
                
        
    }
    
    
    public function testConstruct(  ) : void {           
        $this->assertInstanceOf(RabbitEntity::class, new RabbitEntity ($this->rabbitIdentities) );                
    }
    
    
    public function testGetIdentities() {
        $entity = new RabbitEntity ( $this->rabbitIdentities );       
        $this->assertInstanceOf(Identities::class, $entity->getIdentities());
        
        
    }
    
    
    public function testSetGetAssoc() {
        $rabbitEntity = new RabbitEntity ($this->rabbitIdentities ); 
        
        $rabbitEntity->setAssociatedCarrotEntities( $this->carrotEntities );
        $carrotEntities = $rabbitEntity->getAssociatedCarrotEntities();
        $this->assertIsArray($carrotEntities);
                
        $rabbitEntity->setAssociatedHoleEntity($this->holeEntity);
        $holeEntity = $rabbitEntity->getAssociatedHoleEntity();
        $this->assertInstanceOf(HoleEntityMock::class, $holeEntity);
             
    }
    
    
    public function testSetGetCeleJmeno() {
         $rabbitEntity = new RabbitEntity ($this->rabbitIdentities );
         $rabbitEntity->setCeleJmeno("JMENO");
         $celeJmeno = $rabbitEntity->getCeleJmeno();
         $this->assertEquals( "JMENO", $celeJmeno);        
    }
    
    
    public function testSetGetPrvekDatetime() {
        $rabbitEntity = new RabbitEntity ($this->rabbitIdentities );
        $dt =  new \DateTime("now");
        $rabbitEntity->setPrvekDatetime($dt);
        $date = $rabbitEntity->getPrvekDatetime();
        $this->assertEquals( $dt, $date );         
    }
    
    
    public function testSetGetPrvekVarchar(){        
        $rabbitEntity = new RabbitEntity ($this->rabbitIdentities );
        $var = "DATAVARCHAR";
        $rabbitEntity->setPrvekVarchar($var);
        $v = $rabbitEntity->getPrvekVarchar();
        $this->assertEquals( $var, $v );        
    }
    
    public function testSetIsPersisted(){        
        $rabbitEntity = new RabbitEntity ($this->rabbitIdentities );
        $rabbitEntity->setPersisted();                
        $this->assertTrue($rabbitEntity->isPersisted() );
        
        $rabbitEntity->setUnpersisted();      
        $this->assertFalse($rabbitEntity->isPersisted() );  
    }

    
    public function testSetIsLocked(){        
        $rabbitEntity = new RabbitEntity ($this->rabbitIdentities );
        $rabbitEntity->lock();             
        $this->assertTrue($rabbitEntity->isLocked() );
        
        $rabbitEntity->unLock();      
        $this->assertFalse($rabbitEntity->isLocked() );  
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
