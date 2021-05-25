<?php
use PHPUnit\Framework\TestCase;

//tyto classy jsou pouzite z modelu
use Model\Entity\Hydrator\OneToOneEntityHydrator;

use Model\Entity\EntityAbstract;
use Model\Entity\Identity\IdentityInterface;
use Model\Entity\Hydrator\Filter\OneToOneFilterInterface;
use Model\Entity\Hydrator\NameHydrator\MethodNameHydratorInterface;
use Model\RowObject\RowObjectInterface;
use Model\Entity\EntityInterface;


class OneToOneFilterMock_forOOHT implements OneToOneFilterInterface {    
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

class MethodNameHydrator_forOOHT implements MethodNameHydratorInterface {    
    public function hydrate(string $name): string {
        return 'set' . ucfirst($name);
    }        
    public function extract(string $name): string {       
        return 'get' . ucfirst($name);
    }    
}



class IdentityMock_forOOHT implements IdentityInterface {
    public function isGenerated() : bool {
        return false;
    }
    public function getKeyAttribute() {
        return ['a'];
    }
    public function getKeyHash() {
        return ['a'=>1];
    }        
    public function setKey( array $keyHash ) {
        return $this;
    }
    public function isEqual( IdentityInterface $identity ) : bool {
        return false;
    }
    public function hasEqualAttribute( IdentityInterface $identity ) : bool {
        return true;
    }
}

interface EntityInterfaceMock_forOOHT extends EntityInterface {       
        public function getCeleJmeno();
        public function getPrvekVarchar();
        public function getPrvekChar();
        public function getPrvekText();
        public function getPrvekInteger();
        public function getPrvekDate(): \DateTime;
        public function getPrvekDatetime(): \DateTime;
        public function getPrvekTimestamp(): \DateTime;
        public function getPrvekBoolean();        
        public function setCeleJmeno( string $celeJmeno) :TestovaciEntityMock_forOOHT;
        public function setPrvekVarchar($prvekVarchar) :TestovaciEntityMock_forOOHT;
        public function setPrvekChar($prvekChar) :TestovaciEntityMock_forOOHT;
        public function setPrvekText($prvekText) :TestovaciEntityMock_forOOHT;
        public function setPrvekInteger($prvekInteger) :TestovaciEntityMock_forOOHT;
        public function setPrvekDate(\DateTime $prvekDate) :TestovaciEntityMock_forOOHT;
        public function setPrvekDatetime(\DateTime $prvekDatetime) :TestovaciEntityMock_forOOHT;
        public function setPrvekTimestamp(\DateTime $prvekTimestamp) :TestovaciEntityMock_forOOHT;
        public function setPrvekBoolean($prvekBoolean) :TestovaciEntityMock_forOOHT;            
} 
class TestovaciEntityMock_forOOHT  extends EntityAbstract implements EntityInterfaceMock_forOOHT   {       
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
    public function setCeleJmeno( string $celeJmeno) :TestovaciEntityMock_forOOHT {
       $this->celeJmeno = $celeJmeno;
       return $this;
    }
    public function setPrvekVarchar($prvekVarchar) :TestovaciEntityMock_forOOHT {
        $this->prvekVarchar = $prvekVarchar;       
        return $this;        
    }
    public function setPrvekChar($prvekChar) :TestovaciEntityMock_forOOHT {
        $this->prvekChar = $prvekChar;
        return $this;        
    }
    public function setPrvekText($prvekText) :TestovaciEntityMock_forOOHT {
        $this->prvekText = $prvekText;
        return $this;        
    }
    public function setPrvekInteger($prvekInteger) :TestovaciEntityMock_forOOHT{
        $this->prvekInteger = $prvekInteger;
        return $this;       
    }
    public function setPrvekDate(\DateTime $prvekDate) :TestovaciEntityMock_forOOHT{
        $this->prvekDate = $prvekDate;
        return $this;        
    }
    public function setPrvekDatetime(\DateTime $prvekDatetime) :TestovaciEntityMock_forOOHT {
        $this->prvekDatetime = $prvekDatetime;
        return $this;        
    }
    public function setPrvekTimestamp(\DateTime $prvekTimestamp) :TestovaciEntityMock_forOOHT {
        $this->prvekTimestamp = $prvekTimestamp;
        return $this;      
    }
    public function setPrvekBoolean($prvekBoolean) :TestovaciEntityMock_forOOHT{
        $this->prvekBoolean = $prvekBoolean;
        return $this;
    }
}
    
class RowObjectMock_forOOHT implements RowObjectInterface {              
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
 
    private $seznamJmen  ;
       
    /**
     * Před každým testem.
     * @return void
     */
    public function setUp(): void {     
         // 1 -  nastaveni "konstant"
        $this->testDateString = "2010-09-08";
        $this->testDate = DateTime::createFromFormat("Y-m-d", $this->testDateString)->setTime(0,0,0,0); 
        $this->testDateTimeString = "2005-06-07 22:23:24";
        $this->testDateTime = DateTime::createFromFormat("Y-m-d H:i:s", $this->testDateTimeString);           
        
        $this->seznamJmen =  [ 
                        "prvekVarchar" ,  "prvekChar" ,  "prvekText" , "prvekInteger" ,  "prvekBoolean" , 
                        "prvekDate", "prvekDatetime", "prvekTimestamp" 
                              ] ;   
    }
    
    /**
     * Hydratuji  objekt TestovaciEntity hodnotami z row objektu.
     */     
    public function testOneToOneHydrate() : void {      
        // 2 - zdrojovy datovy objekt testovaci
        $testovaciZdrojovyRowObjectNaplneny = new RowObjectMock_forOOHT();                    
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
        $oneToOneEntityHydrator = new OneToOneEntityHydrator( new MethodNameHydrator_forOOHT(),
                                                              new OneToOneFilterMock_forOOHT( $this->seznamJmen) );      
                
        // 4 -  hydratovani
        $identity = new IdentityMock_forOOHT(  ); //['b']
        $novaPlnenaTestovaciEntity  =  new TestovaciEntityMock_forOOHT(  $identity  );           
        $oneToOneEntityHydrator->hydrate( $novaPlnenaTestovaciEntity, $testovaciZdrojovyRowObjectNaplneny );                
                
        // 5 - kontrola hydratace           
        foreach (  $this->seznamJmen as  $value )  /* $value  je vlastnost rowobjectu!!!!!*/    {             
            // assertEquals (ocekavana, aktualni hodnota v entite)              
            $this->assertEquals( $testovaciZdrojovyRowObjectNaplneny->$value, 
                                 $testovaciZdrojovyRowObjectNaplneny->$value, " *CHYBA* ");         
        }          
        
        print_r ( "\n *************** obsah Identity - vlastne IdentityMock - vypisuje bez obsahu, zadny tam neni.(protoze IdentityMock nema constructor, kde by se nastavily atributy) " );
        print_r ( "\n promenna novaPlnenaTestovaciEntity je mock objekt vznikly s Identity(prazdnou) . Pozn.Je tam EntityAbstract constructor.\n ");
        print_r  ( $novaPlnenaTestovaciEntity->getIdentity()  ); 
        
        print_r ( "\n ***************novaPlnenaTestovaciEntity->getIdentity->getKeyAttribute -  obsah KeyAttribute - vypisuje nasvindlovany return  \n"  );
        print_r  ( $novaPlnenaTestovaciEntity->getIdentity()->getKeyAttribute()  ); // vraci z  IdentityMock objektu , tam je nasvindlovany return 
        print_r ( "\n " );
        print_r ( "\n **** nove naplnena Testovaci Entity   **** \n" );
        print_r( $novaPlnenaTestovaciEntity ); 
        print_r ( "\n ---------------------------------------------- \n" );       
    }          
    
    
            
    /**
     * Extrahuji z objektu TestovaciEntity do row objektu.
     */     
    public function testOneToOneExtract(): void {             
        // 2 - zdrojovy datovy objekt testovaci
        $testovaciZdrojovaEntityNaplnena = new TestovaciEntityMock_forOOHT ( new IdentityMock_forOOHT(  ['a'] ) );              
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
        $oneToOneEntityHydrator = new OneToOneEntityHydrator( new MethodNameHydrator_forOOHT(),
                                                              new OneToOneFilterMock_forOOHT( $this->seznamJmen) );       //vlastnosti rowobjectu!!!!! 
        
        // 4 -  extrakce
        $novyPlnenyRowObject  =  new RowObjectMock_forOOHT ();           
        $oneToOneEntityHydrator->extract( $testovaciZdrojovaEntityNaplnena, $novyPlnenyRowObject );        
                
        // 5 - kontrola extrakce     
        foreach (  $this->seznamJmen as  $value )  /* $value je vlastnost rowobjectu!!!!!*/ {                     
            //$getMethodName = "get" .  ucfirst( $value );  
            $methodNameHydrator = new MethodNameHydrator_forOOHT();
            $getMethodName = $methodNameHydrator->extract($value);
            // assertEquals (ocekavana, aktualni hodnota v row objektu)   
            $this->assertEquals( $testovaciZdrojovaEntityNaplnena->$getMethodName(),  /*zde nejak jhinak   ?????   */
                                 $novyPlnenyRowObject->$value ,                  
                                 "*CHYBA*při extrahovani");     
        }    
        
        print_r ( "\n **** nove naplneny Row Object  **** \n" );
        print_r ( $novyPlnenyRowObject );
        
    }
}


