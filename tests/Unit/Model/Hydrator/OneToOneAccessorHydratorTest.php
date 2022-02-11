<?php
namespace Test\OneToOneEntityHydratorTest;

use PHPUnit\Framework\TestCase;

//tyto classy jsou pouzite z modelu
use Model\Hydrator\OneToOneAccessorHydrator;
use Model\Filter\OneToOneFilterInterface;
use Model\Hydrator\NameHydrator\AccessorMethodNameHydratorInterface;

use Model\Entity\EntityAbstract;
use Model\Entity\EntityInterface;
use Model\Entity\Identity\IdentityAbstract;
use Model\Entity\Identity\IdentityInterface;

use Model\Entity\Identities;
use Model\Entity\Enum\IdentityTypeEnum;

use Model\RowObject\RowObjectAbstract;
use Model\RowObject\RowObjectInterface;

use Model\RowObject\Key\KeyAbstract;
use Model\RowObject\Key\KeyInterface;


class OneToOneFilterMock implements OneToOneFilterInterface {    
    /**
     * @var array
     */
    private $poleJmen;                   
    public function __construct(  array $poleJmen ) { 
        $this->poleJmen = $poleJmen; 
    }
    //Pozn. - getIterator vrací iterovatelný objekt.        
    public function getIterator() : \Traversable{        
        return new \ArrayIterator(  $this->poleJmen   );
    }        
}

class MethodNameHydrator_Mock implements AccessorMethodNameHydratorInterface {    
    public function hydrate(string $name): string {
        return 'set' . ucfirst($name);
    }        
    public function extract(string $name): string {       
        return 'get' . ucfirst($name);
    }    
}

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




interface IdentityInterfaceMock extends IdentityInterface {
    public function setUidPrimarniKlicZnaky( string $uidPrimarniKlicZnaky): IdentityMock ; 
    public function getUidPrimarniKlicZnaky() :string ;
}

//class IdentityMock extends IdentityAbstract implements  IdentityInterfaceMock {
//
//    private  $uidPrimarniKlicZnaky;
//    
//    public function getTypeIdentity(): string {
//    }
//
//    
//    public function setUidPrimarniKlicZnaky( string $uidPrimarniKlicZnaky): IdentityMock {
//        $this->uidPrimarniKlicZnaky = $uidPrimarniKlicZnaky;
//        return $this;
//    }
//    public function getUidPrimarniKlicZnaky() :string {
//        return $this->uidPrimarniKlicZnaky;
//    }        
//
//}

interface EntityInterfaceMock extends EntityInterface  {
        public function getCeleJmeno();        
        public function getPrvekVarchar();
        public function getPrvekChar();
        public function getPrvekText();
        public function getPrvekInteger();
        public function getPrvekDate(): \DateTime;
        public function getPrvekDatetime(): \DateTime;
        public function getPrvekTimestamp(): \DateTime;
        public function getPrvekBoolean();        
        
        public function setCeleJmeno( string $celeJmeno) :TestovaciEntityMock;
        public function setPrvekVarchar($prvekVarchar) :TestovaciEntityMock;
        public function setPrvekChar($prvekChar) :TestovaciEntityMock;
        public function setPrvekText($prvekText) :TestovaciEntityMock;
        public function setPrvekInteger($prvekInteger) :TestovaciEntityMock;
        public function setPrvekDate(\DateTime $prvekDate) :TestovaciEntityMock;
        public function setPrvekDatetime(\DateTime $prvekDatetime) :TestovaciEntityMock;
        public function setPrvekTimestamp(\DateTime $prvekTimestamp) :TestovaciEntityMock;
        public function setPrvekBoolean($prvekBoolean) :TestovaciEntityMock; 
} 
class TestovaciEntityMock  extends EntityAbstract implements  EntityInterfaceMock   {       
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
        
//------------------------------------------------------
     
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
    public function getPrvekInteger() : int {
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
    public function setCeleJmeno( string $celeJmeno) :TestovaciEntityMock {
       $this->celeJmeno = $celeJmeno;
       return $this;
    }
    public function setPrvekVarchar($prvekVarchar) :TestovaciEntityMock {
        $this->prvekVarchar = $prvekVarchar;       
        return $this;        
    }
    public function setPrvekChar($prvekChar) :TestovaciEntityMock {
        $this->prvekChar = $prvekChar;
        return $this;        
    }
    public function setPrvekText($prvekText) :TestovaciEntityMock {
        $this->prvekText = $prvekText;
        return $this;        
    }
    public function setPrvekInteger($prvekInteger) :TestovaciEntityMock{
        $this->prvekInteger = $prvekInteger;
        return $this;       
    }
    public function setPrvekDate(\DateTime $prvekDate) :TestovaciEntityMock{
        $this->prvekDate = $prvekDate;
        return $this;        
    }
    public function setPrvekDatetime(\DateTime $prvekDatetime) :TestovaciEntityMock {
        $this->prvekDatetime = $prvekDatetime;
        return $this;        
    }
    public function setPrvekTimestamp(\DateTime $prvekTimestamp) :TestovaciEntityMock {
        $this->prvekTimestamp = $prvekTimestamp;
        return $this;      
    }
    public function setPrvekBoolean($prvekBoolean) :TestovaciEntityMock {
        $this->prvekBoolean = $prvekBoolean;
        return $this;
    }
}
 


interface KeyInterfaceMock extends KeyInterface{   
    public function setHash( array $hash): void ;
    public function getHash(): array ;
    public function getGenerated(): array ;
    public function setGenerated( array  $generated ): void ;

    public function isEqual( KeyInterface $key ) : bool;
    
}
class KeyMock extends KeyAbstract implements KeyInterfaceMock {
    public $uidPrimarniKlicZnaky;

    public function setHash( array $hash): void {}
    public function getHash(): array {}
    public function getGenerated(): array {}
    public function setGenerated( array  $generated ): void {}

    public function isEqual( KeyInterface $key ) : bool{}    
}

interface RowObjectInterfaceMock extends RowObjectInterface{    
}
class RowObjectMock extends RowObjectAbstract implements RowObjectInterfaceMock {                  
    public $titulPred;
    public $jmeno;
    public $prijmeni;
    public $titulZa;
    
    public $prvekChar;
    public $prvekVarchar;  
    public $prvekText;
    public $prvekInteger;   
    public $prvekBoolean;  
    /**
     * @var \DateTime 
     */
    public $prvekDate;
    /**     
     * @var \DateTime 
     */
    public $prvekDatetime;
    /**
     * @var \DateTime 
     */
    public $prvekTimestamp;    
}

//------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------

/**
 * Description of EntityHydratorTest
 *
 * @author vlse2610
 */
class OneToOneEntityHydratorTest extends TestCase {    
    private $testDateString;
    private $testDate;
    private $testDateTimeString;
    private $testDateTime;
 
//    private $poleJmen  ;
       
    /**
     * Před každým testem.
     * @return void
     */
    public function setUp(): void {     
         // 1 -  nastaveni "konstant"
        $this->testDateString = "2010-09-08";
        $this->testDate = \DateTime::createFromFormat("Y-m-d", $this->testDateString)->setTime(0,0,0,0); 
        $this->testDateTimeString = "2005-06-07 22:23:24";
        $this->testDateTime = \DateTime::createFromFormat("Y-m-d H:i:s", $this->testDateTimeString);                          
    }
    
    /**
     * Hydratuji  objekt TestovaciEntity hodnotami z row objektu.
     */     
    public function testOneToOneEntityHydrate() : void {      
        $poleJmenDoFiltruAccessoru =  [ 
            "prvekChar" , "prvekVarchar", "prvekInteger" ,"prvekText", "prvekBoolean",  
            "prvekDate", "prvekDatetime", "prvekTimestamp"           ] ;   
        $poleJmenKlice = [ "uidPrimarniKlicZnaky" ] ;
   
        
        // 2 - zdrojovy datovy objekt testovaci        
        $testovaciZdrojovyRowObjectNaplneny = new RowObjectMock ( /*new KeyMock(*/ [ 'uidPrimarniKlicZnaky' => false ] /*)*/ );   // pole je do -generated
        //$testovaciZdrojovyRowObjectNaplneny->key->uidPrimarniKlicZnaky = 'klicekk';
        
        $testovaciZdrojovyRowObjectNaplneny->jmeno          = "BARNABÁŠ";
        $testovaciZdrojovyRowObjectNaplneny->prijmeni       = "KOSTKA"; 
        $testovaciZdrojovyRowObjectNaplneny->titulPred      = "MUDrC.";
        $testovaciZdrojovyRowObjectNaplneny->titulZa        = "vezír";               
        $testovaciZdrojovyRowObjectNaplneny->prvekChar      = "CHARY *testovaci*";
        $testovaciZdrojovyRowObjectNaplneny->prvekVarchar   = "VARCHARY-testovaci";
        $testovaciZdrojovyRowObjectNaplneny->prvekText      = "TEXTY/ testovaci /";
        $testovaciZdrojovyRowObjectNaplneny->prvekInteger   = 666;   
        $testovaciZdrojovyRowObjectNaplneny->prvekBoolean   = \TRUE;        
        $testovaciZdrojovyRowObjectNaplneny->prvekDate      = $this->testDate ; //$this->DateTime::createFromFormat("Y-m-d", $testDateString)->setTime(0,0,0,0);         // objekt    
        $testovaciZdrojovyRowObjectNaplneny->prvekDatetime  = $this->testDateTime ; //$this->DateTime::createFromFormat("Y-m-d H:i:s", $testDateTimeString);      // objekt         
        $testovaciZdrojovyRowObjectNaplneny->prvekTimestamp = $this->testDateTime ;  //$this->DateTime::createFromFormat("Y-m-d H:i:s", $testDateTimeString);      // objekt             

       
                     
        // 3 - filtr, name hydrator, -> vytvoření hydratoru       
        $oneToOneEntityHydrator = new OneToOneAccessorHydrator( new MethodNameHydrator_Mock(),
                                                                new OneToOneFilterMock( $poleJmenDoFiltruAccessoru) );      
        $oneToOneIdentityHydrator = new OneToOneAccessorHydrator( new MethodNameHydrator_Mock(),
                                                                  new OneToOneFilterMock( $poleJmenKlice) ); 
                
        // 4 -  hydratovani
         //$identity = new IdentityMock(  );
        $rabbitIdentitiesA[RabbitIdentityNamesEnumMock::RABBITIDENTITYINTERFACEMOCK ]  = new RabbitIdentityMock ( );
        $rabbitIdentitiesA[RabbitIdentityNamesEnumMock::KLICIDENTITYINTERFACEMOCK ]  = new KlicIdentityMock ( );
        $rabbitIdentities = new Identities(new RabbitIdentityNamesEnumMock(), $rabbitIdentitiesA );   
      
        $novaPlnenaTestovaciEntity  =  new TestovaciEntityMock( $rabbitIdentities );   
        
        $oneToOneEntityHydrator->hydrate( $novaPlnenaTestovaciEntity, $testovaciZdrojovyRowObjectNaplneny );          
        //$oneToOneIdentityHydrator->hydrate( $novaPlnenaTestovaciEntity->getIdentity(), $testovaciZdrojovyRowObjectNaplneny->key ); 
       
        
        // 5 - kontrola hydratace  - entita        
        $oneToOneFilter = new OneToOneFilterMock( $poleJmenDoFiltruAccessoru);
        foreach ( $oneToOneFilter->getIterator() as  $value )  /* $value  je vlastnost rowobjectu!!!!!*/    {             
            $methodNameHydrator = new MethodNameHydrator_Mock();
            $methodNameGet = $methodNameHydrator->extract( $value );
            
            // assertEquals (ocekavana, aktualni hodnota v entite) 
            $this->assertEquals($testovaciZdrojovyRowObjectNaplneny->$value, 
                                $novaPlnenaTestovaciEntity->$methodNameGet(),  " *CHYBA*při hydrataci");         
        }                   
        $this->assertEquals( $testovaciZdrojovyRowObjectNaplneny->prvekVarchar, $novaPlnenaTestovaciEntity->getPrvekVarchar(), " *CHYBA*při hydrataci");
        $this->assertEquals( $testovaciZdrojovyRowObjectNaplneny->prvekChar, $novaPlnenaTestovaciEntity->getPrvekChar(), " *CHYBA*při hydrataci");
        $this->assertEquals( $testovaciZdrojovyRowObjectNaplneny->prvekText, $novaPlnenaTestovaciEntity->getPrvekText(), " *CHYBA*při hydrataci");
        $this->assertEquals( $testovaciZdrojovyRowObjectNaplneny->prvekInteger, $novaPlnenaTestovaciEntity->getPrvekInteger(), " *CHYBA*při hydrataci");
        $this->assertEquals( $testovaciZdrojovyRowObjectNaplneny->prvekBoolean, $novaPlnenaTestovaciEntity->getPrvekBoolean()," *CHYBA*při hydrataci");
        $this->assertEquals( $testovaciZdrojovyRowObjectNaplneny->prvekDate, $novaPlnenaTestovaciEntity->getPrvekDate(), " *CHYBA*při hydrataci");
        $this->assertEquals( $testovaciZdrojovyRowObjectNaplneny->prvekDatetime, $novaPlnenaTestovaciEntity->getPrvekDatetime(), " *CHYBA*při hydrataci");
        $this->assertEquals( $testovaciZdrojovyRowObjectNaplneny->prvekTimestamp, $novaPlnenaTestovaciEntity->getPrvekTimestamp(), " *CHYBA*při hydrataci");
        
//        // 5 - kontrola hydratace  - identita        
//        $oneToOneFilter1 = new OneToOneFilterMock( $poleJmenKlice);
//        foreach ( $oneToOneFilter1->getIterator() as  $value )  /* $value  je vlastnost identity!!!!!*/    {             
//                    //$methodNameHydrator = new MethodNameHydrator_Mock();
//            $methodNameGet = $methodNameHydrator->extract( $value );          
//            
//            // assertEquals (ocekavana, aktualni hodnota v entite) 
//            $this->assertEquals($testovaciZdrojovyRowObjectNaplneny->key->$value, 
//                                $novaPlnenaTestovaciEntity->getIdentity()->$methodNameGet(),  " *CHYBA*při hydrataci");         
//        }                   
//        $this->assertEquals( $testovaciZdrojovyRowObjectNaplneny->key->uidPrimarniKlicZnaky, $novaPlnenaTestovaciEntity->getIdentity()->$methodNameGet(), " *CHYBA*při hydrataci");        
    }          
    
    
            
    /**
     * Extrahuji z objektu TestovaciEntity do row objektu.
     */     
    public function testOneToOneEntityExtract(): void {     
        $poleJmenDoFiltruAccessoru =  [ 
            "prvekChar" , "prvekVarchar", "prvekInteger" ,"prvekText", "prvekBoolean",  
            "prvekDate", "prvekDatetime", "prvekTimestamp"           ] ;   
        $poleJmenKlice = [ "uidPrimarniKlicZnaky" ] ;
        
        $rabbitIdentitiesA[RabbitIdentityNamesEnumMock::RABBITIDENTITYINTERFACEMOCK ]  = new RabbitIdentityMock ( );
        $rabbitIdentitiesA[RabbitIdentityNamesEnumMock::KLICIDENTITYINTERFACEMOCK ]  = new KlicIdentityMock ( );
        $rabbitIdentities = new Identities(new RabbitIdentityNamesEnumMock(), $rabbitIdentitiesA );   
      
        
        
        // 2 - zdrojovy datovy objekt testovaci - entita
        $testovaciZdrojovaEntityNaplnena = new TestovaciEntityMock ( $rabbitIdentities  );              
        $testovaciZdrojovaEntityNaplnena->setCeleJmeno(      "BARNABÁŠ " . "KOSTKA" );        
        $testovaciZdrojovaEntityNaplnena->setPrvekChar(      "CHARY *testovaci*" );
        $testovaciZdrojovaEntityNaplnena->setPrvekVarchar(   "VARCHARY -testovaci-" );
        $testovaciZdrojovaEntityNaplnena->setPrvekText(      "TEXTY /testovaci/"); 
        $testovaciZdrojovaEntityNaplnena->setPrvekInteger(   666 );
        $testovaciZdrojovaEntityNaplnena->setPrvekBoolean(   \TRUE );
        $testovaciZdrojovaEntityNaplnena->setPrvekDate(      $this->testDate); //  DateTime::createFromFormat("Y-m-d", $testDateString)->setTime(0,0,0,0) );  
        $testovaciZdrojovaEntityNaplnena->setPrvekDatetime(  $this->testDateTime ); //DateTime::createFromFormat("Y-m-d H:i:s", $testDateTimeString)  );                       
        $testovaciZdrojovaEntityNaplnena->setPrvekTimestamp( $this->testDateTime ); //DateTime::createFromFormat("Y-m-d H:i:s", $testDateTimeString)  );                        
                          
        //$testovaciZdrojovaEntityNaplnena->getIdentity()->setUidPrimarniKlicZnaky ('klicekk');
       
        // 3 - filtr, nastaveni filtru, hydrator (filtr do hydratoru)                                               
        $oneToOneEntityHydrator = new OneToOneAccessorHydrator( new MethodNameHydrator_Mock(),
                                                                new OneToOneFilterMock( $poleJmenDoFiltruAccessoru ) );       //vlastnosti rowobjectu!!!!! 
        $oneToOneIdentityHydrator = new OneToOneAccessorHydrator( new MethodNameHydrator_Mock(),
                                                                  new OneToOneFilterMock( $poleJmenKlice ) );       //vlastnosti rowobjectu->key!!!!! 
        
        // 4 -  extrakce                           
        $novyPlnenyRowObject  =  new RowObjectMock (  ['uidPrimarniKlicZnaky' => false ]  );           
        $oneToOneEntityHydrator->extract( $testovaciZdrojovaEntityNaplnena, $novyPlnenyRowObject );    
        //$oneToOneIdentityHydrator->extract( $testovaciZdrojovaEntityNaplnena->getIdentity(), $novyPlnenyRowObject->key) ;    
                
        // 5 - kontrola extrakce   - rowObject  
        $oneToOneFilter = new OneToOneFilterMock( $poleJmenDoFiltruAccessoru );
        foreach (   $oneToOneFilter->getIterator()  as  $value )  /* $value je vlastnost rowobjectu!!!!!*/ {         
            $methodNameHydrator = new MethodNameHydrator_Mock();
            $methodNameGet = $methodNameHydrator->extract( $value );
            //$getMethodName = "get" .  ucfirst( $value );     
            
            // assertEquals (ocekavana, aktualni hodnota v row objektu)   
            $this->assertEquals($testovaciZdrojovaEntityNaplnena->$methodNameGet(),  
                                $novyPlnenyRowObject->$value ,     "*CHYBA*při extrahování");     
        }                            
        $this->assertEquals( $testovaciZdrojovaEntityNaplnena->getPrvekVarchar(), $novyPlnenyRowObject->prvekVarchar, "*CHYBA*při extrahovani"); 
        $this->assertEquals( $testovaciZdrojovaEntityNaplnena->getPrvekChar(), $novyPlnenyRowObject->prvekChar, "*CHYBA*při extrahovani"); 
        $this->assertEquals( $testovaciZdrojovaEntityNaplnena->getPrvekText(), $novyPlnenyRowObject->prvekText, "*CHYBA*při extrahovani"); 
        $this->assertEquals( $testovaciZdrojovaEntityNaplnena->getPrvekInteger(), $novyPlnenyRowObject->prvekInteger, "*CHYBA*při extrahovani"); 
        $this->assertEquals( $testovaciZdrojovaEntityNaplnena->getPrvekBoolean(), $novyPlnenyRowObject->prvekBoolean, "*CHYBA*při extrahovani"); 
        $this->assertEquals( $testovaciZdrojovaEntityNaplnena->getPrvekDate(), $novyPlnenyRowObject->prvekDate, "*CHYBA*při extrahovani"); 
        $this->assertEquals( $testovaciZdrojovaEntityNaplnena->getPrvekDatetime(), $novyPlnenyRowObject->prvekDatetime, "*CHYBA*při extrahovani"); 
        $this->assertEquals( $testovaciZdrojovaEntityNaplnena->getPrvekTimestamp(), $novyPlnenyRowObject->prvekTimestamp, "*CHYBA*při extrahovani"); 
                        
//        // 5 - kontrola extrakce   - rowObject-key 
//        $oneToOneFilter = new OneToOneFilterMock( $poleJmenKlice );
//        foreach ( $oneToOneFilter->getIterator()  as  $value )  /* $value je vlastnost rowobjectu-key!!!!!*/ {         
//                    //$methodNameHydrator = new MethodNameHydrator_Mock();
//            $methodNameGet = $methodNameHydrator->extract( $value );
//                       
//            // assertEquals (ocekavana, aktualni hodnota v row objektu)   
//            $this->assertEquals($testovaciZdrojovaEntityNaplnena->getIdentity()->$methodNameGet(),  
//                                $novyPlnenyRowObject->key->$value ,     "*CHYBA*při extrahování");     
//        }                            
//   
//        $this->assertEquals( $testovaciZdrojovaEntityNaplnena->getIdentity()->getUidPrimarniKlicZnaky(), $novyPlnenyRowObject->key->uidPrimarniKlicZnaky, "*CHYBA*při extrahovani");                     

        }
}


