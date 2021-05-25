<?php
namespace Test\OneToOneEntityHydratorTest;

use PHPUnit\Framework\TestCase;

//tyto classy jsou pouzite z modelu
use Model\Entity\Hydrator\OneToOneEntityHydrator;

use Model\Entity\Identity\IdentityInterface;
use Model\Entity\Identity\Key\KeyInterface;
use Model\Entity\Hydrator\Filter\OneToOneFilterInterface;
use Model\Entity\Hydrator\NameHydrator\MethodNameHydratorInterface;
use Model\Entity\EntityInterface;
use Model\Entity\EntityAbstract;
use Model\RowObject\RowObjectInterface;
use Model\RowObject\RowObjectAbstract;

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

class MethodNameHydrator_Mock implements MethodNameHydratorInterface {    
    public function hydrate(string $name): string {
        return 'set' . ucfirst($name);
    }        
    public function extract(string $name): string {       
        return 'get' . ucfirst($name);
    }    
}


    
class IdentityMock implements IdentityInterface {
    public function hasGeneratedKey() : bool {
        return false;
    }
    public function getKey(): KeyInterface{ 
        return $this->key;
    }    
    public function setKey( KeyInterface $key): void {
        $this->key = $key;
    }
 
}

interface EntityInterfaceMock extends EntityInterface {
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
class TestovaciEntityMock  extends EntityAbstract implements EntityInterfaceMock   {       
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
    
class RowObjectMock extends RowObjectAbstract implements RowObjectInterface {              
    public $uidPrimarniKlicZnaky ;         

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
 
    private $poleJmen  ;
       
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
        
        $this->poleJmen =  [ 
                        "prvekVarchar" ,  "prvekChar" ,  "prvekText" , "prvekInteger" ,  "prvekBoolean" , 
                        "prvekDate", "prvekDatetime", "prvekTimestamp" 
                              ] ;   
    }
    
    /**
     * Hydratuji  objekt TestovaciEntity hodnotami z row objektu.
     */     
    public function testOneToOneEntityHydrate() : void {      
        // 2 - zdrojovy datovy objekt testovaci
        $testovaciZdrojovyRowObjectNaplneny = new RowObjectMock();                    
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
        $oneToOneEntityHydrator = new OneToOneEntityHydrator( new MethodNameHydrator_Mock(),
                                                              new OneToOneFilterMock( $this->poleJmen) );      
                
        // 4 -  hydratovani
        $identity = new IdentityMock(  );
        $novaPlnenaTestovaciEntity  =  new TestovaciEntityMock( $identity  );           
        $oneToOneEntityHydrator->hydrate( $novaPlnenaTestovaciEntity, $testovaciZdrojovyRowObjectNaplneny );                
                
        // 5 - kontrola hydratace          
        $oneToOneFilter = new OneToOneFilterMock( $this->poleJmen);
        foreach (  $oneToOneFilter->getIterator() as  $value )  /* $value  je vlastnost rowobjectu!!!!!*/    {             
            $methodNameHydrator = new MethodNameHydrator_Mock();
            $methodNameGet = $methodNameHydrator->extract( $value );
            //$getMethodName = "get" .  ucfirst( $value );
            //
            // assertEquals (ocekavana, aktualni hodnota v entite) 
            $this->assertEquals($testovaciZdrojovyRowObjectNaplneny->$value, 
                                $novaPlnenaTestovaciEntity->$methodNameGet(), 
                                " *CHYBA*při hydrataci");         
        }           
        
        
//         "prvekVarchar" ,  "prvekChar" ,  "prvekText" , "prvekInteger" ,  "prvekBoolean" , 
//         "prvekDate", "prvekDatetime", "prvekTimestamp" 

        $this->assertEquals( $testovaciZdrojovyRowObjectNaplneny->prvekVarchar, $novaPlnenaTestovaciEntity->getPrvekVarchar(), " *CHYBA*při hydrataci");
        $this->assertEquals( $testovaciZdrojovyRowObjectNaplneny->prvekChar, $novaPlnenaTestovaciEntity->getPrvekChar(), " *CHYBA*při hydrataci");
        $this->assertEquals( $testovaciZdrojovyRowObjectNaplneny->prvekText, $novaPlnenaTestovaciEntity->getPrvekText(), " *CHYBA*při hydrataci");
        $this->assertEquals( $testovaciZdrojovyRowObjectNaplneny->prvekInteger, $novaPlnenaTestovaciEntity->getPrvekInteger(), " *CHYBA*při hydrataci");
        $this->assertEquals( $testovaciZdrojovyRowObjectNaplneny->prvekBoolean, $novaPlnenaTestovaciEntity->getPrvekBoolean()," *CHYBA*při hydrataci");
        $this->assertEquals( $testovaciZdrojovyRowObjectNaplneny->prvekDate, $novaPlnenaTestovaciEntity->getPrvekDate(), " *CHYBA*při hydrataci");
        $this->assertEquals( $testovaciZdrojovyRowObjectNaplneny->prvekDatetime, $novaPlnenaTestovaciEntity->getPrvekDatetime(), " *CHYBA*při hydrataci");
        $this->assertEquals( $testovaciZdrojovyRowObjectNaplneny->prvekTimestamp, $novaPlnenaTestovaciEntity->getPrvekTimestamp(), " *CHYBA*při hydrataci");
        
        
    }          
    
    
            
    /**
     * Extrahuji z objektu TestovaciEntity do row objektu.
     */     
    public function testOneToOneEntityExtract(): void {             
        // 2 - zdrojovy datovy objekt testovaci
        $testovaciZdrojovaEntityNaplnena = new TestovaciEntityMock ( new IdentityMock(  ) );              
        $testovaciZdrojovaEntityNaplnena->setCeleJmeno(      "BARNABÁŠ " . "KOSTKA" );        
        $testovaciZdrojovaEntityNaplnena->setPrvekChar(      "CHARY *testovaci*" );
        $testovaciZdrojovaEntityNaplnena->setPrvekVarchar(   "VARCHARY -testovaci-" );
        $testovaciZdrojovaEntityNaplnena->setPrvekText(      "TEXTY /testovaci/"); 
        $testovaciZdrojovaEntityNaplnena->setPrvekInteger(   666 );
        $testovaciZdrojovaEntityNaplnena->setPrvekBoolean(   \TRUE );
        $testovaciZdrojovaEntityNaplnena->setPrvekDate(      $this->testDate); //  DateTime::createFromFormat("Y-m-d", $testDateString)->setTime(0,0,0,0) );  
        $testovaciZdrojovaEntityNaplnena->setPrvekDatetime(  $this->testDateTime ); //DateTime::createFromFormat("Y-m-d H:i:s", $testDateTimeString)  );                       
        $testovaciZdrojovaEntityNaplnena->setPrvekTimestamp( $this->testDateTime ); //DateTime::createFromFormat("Y-m-d H:i:s", $testDateTimeString)  );                        
                  
        // 3 - filtr, nastaveni filtru, hydrator (filtr do hydratoru)                                               
        $oneToOneEntityHydrator = new OneToOneEntityHydrator( new MethodNameHydrator_Mock(),
                                                              new OneToOneFilterMock( $this->poleJmen) );       //vlastnosti rowobjectu!!!!! 
        
        // 4 -  extrakce
        $novyPlnenyRowObject  =  new RowObjectMock ();           
        $oneToOneEntityHydrator->extract( $testovaciZdrojovaEntityNaplnena, $novyPlnenyRowObject );        
                
        // 5 - kontrola extrakce     
        $oneToOneFilter = new OneToOneFilterMock( $this->poleJmen);
        foreach (   $oneToOneFilter->getIterator()  as  $value )  /* $value je vlastnost rowobjectu!!!!!*/ {         
            $methodNameHydrator = new MethodNameHydrator_Mock();
            $methodNameGet = $methodNameHydrator->extract( $value );
            //$getMethodName = "get" .  ucfirst( $value );     
            //
            // assertEquals (ocekavana, aktualni hodnota v row objektu)   
            $this->assertEquals($testovaciZdrojovaEntityNaplnena->$methodNameGet(),  
                                $novyPlnenyRowObject->$value ,                  
                                "*CHYBA*při extrahování");     
        }    
        
                
//         "prvekVarchar" ,  "prvekChar" ,  "prvekText" , "prvekInteger" ,  "prvekBoolean" , 
//         "prvekDate", "prvekDatetime", "prvekTimestamp" 
        $this->assertEquals( $testovaciZdrojovaEntityNaplnena->getPrvekVarchar(), $novyPlnenyRowObject->prvekVarchar, "*CHYBA*při extrahovani"); 
        $this->assertEquals( $testovaciZdrojovaEntityNaplnena->getPrvekChar(), $novyPlnenyRowObject->prvekChar, "*CHYBA*při extrahovani"); 
        $this->assertEquals( $testovaciZdrojovaEntityNaplnena->getPrvekText(), $novyPlnenyRowObject->prvekText, "*CHYBA*při extrahovani"); 
        $this->assertEquals( $testovaciZdrojovaEntityNaplnena->getPrvekInteger(), $novyPlnenyRowObject->prvekInteger, "*CHYBA*při extrahovani"); 
        $this->assertEquals( $testovaciZdrojovaEntityNaplnena->getPrvekBoolean(), $novyPlnenyRowObject->prvekBoolean, "*CHYBA*při extrahovani"); 
        $this->assertEquals( $testovaciZdrojovaEntityNaplnena->getPrvekDate(), $novyPlnenyRowObject->prvekDate, "*CHYBA*při extrahovani"); 
        $this->assertEquals( $testovaciZdrojovaEntityNaplnena->getPrvekDatetime(), $novyPlnenyRowObject->prvekDatetime, "*CHYBA*při extrahovani"); 
        $this->assertEquals( $testovaciZdrojovaEntityNaplnena->getPrvekTimestamp(), $novyPlnenyRowObject->prvekTimestamp, "*CHYBA*při extrahovani"); 

        }
}


