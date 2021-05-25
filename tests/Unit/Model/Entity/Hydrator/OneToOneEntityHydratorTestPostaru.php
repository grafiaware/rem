<?php
//use PHPUnit\Framework\TestCase;

//tzto classy jsou pouzite z modelu
use Model\Entity\Hydrator\OneToOneEntityHydrator;
use Model\Entity\Hydrator\Filter\OneToOneFilter;
use Model\Entity\Hydrator\GetMethodNameHydrator;
//use Model\Entity\Hydrator\SetMethodNameHydrator;

use Model\Entity\EntityInterface;
use Model\Entity\EntityAbstract;
use Model\RowObject\RowObjectInterface;
use Model\Entity\Identity\IdentityInterface;
//------------------------------------------------------------------------------------

/**
 * Description of EntityHydratorTest
 *
 * @author vlse2610
 */
class OneToOneEntityHydratorTestPostaru extends TestCase {    
    private $testDateString;
    private $testDate;
    private $testDateTimeString;
    private $testDateTime;
    
    /** @var Identity $identity */  
    private $identity;
    
    /** @var TestovaciRowObject $testovaciZdrojovyRowObject */  
    private $testovaciZdrojovyRowObject;
    /** @var TestovaciEntity $testovaciZdrojovaEntity */ 
    private $testovaciZdrojovaEntity;
    /** @var array  - seznam jmen vlastností (row objektu) k hydrataci/extrakci z  entity */
    private $seznamJmen;
   
    
    /**
     * Před každým testem.
     * @return void
     */
    public function setUp(): void { 
        $this->testDateString = "2010-09-08";
        $this->testDate = DateTime::createFromFormat("Y-m-d", $this->testDateString)->setTime(0,0,0,0); 
        $this->testDateTimeString = "2005-06-07 22:23:24";
        $this->testDateTime = DateTime::createFromFormat("Y-m-d H:i:s", $this->testDateTimeString);       
        
        // 1 - naplneni testovaciho (zdrojoveho) row objektu        
        $this->testovaciZdrojovyRowObject = new  TestovaciRowObject();            
        $this->testovaciZdrojovyRowObject->uidPrimarniKlicZnaky = "AAA*primarniKlic";        
        $this->testovaciZdrojovyRowObject->jmeno          = "BARNABÁŠ";
        $this->testovaciZdrojovyRowObject->prijmeni       = "KOSTKA"; 
        $this->testovaciZdrojovyRowObject->titulPred      = "MUDrC.";
        $this->testovaciZdrojovyRowObject->titulZa        = "vezír";               
        $this->testovaciZdrojovyRowObject->prvekChar      = "CHARY *testovaci*";
        $this->testovaciZdrojovyRowObject->prvekVarchar   = "VARCHARY-testovaci";
        $this->testovaciZdrojovyRowObject->prvekText      = "TEXTY/ testovaci /";
        $this->testovaciZdrojovyRowObject->prvekInteger   = 666;   
        $this->testovaciZdrojovyRowObject->prvekBoolean   = \TRUE;        
        $this->testovaciZdrojovyRowObject->prvekDate      = $this->testDate;        // objekt    
        $this->testovaciZdrojovyRowObject->prvekDatetime  = $this->testDateTime;    // objekt         
        $this->testovaciZdrojovyRowObject->prvekTimestamp = $this->testDateTime;    // objekt                                   
//        foreach ($this->poleHodnot as $key => $value) {
//            $this->testovaciRowObject->$key = $value /*$this->poleHodnot[ $key ]*/;
//        }  
        
                        
        //$this->testovaciAttribute = [ 'Klic1' ,'Klic2'  ];               
        $this->identity = new Identity( [ 'Klic1' ,'Klic2'  ] ); //neni generovany klic    
    
        
        // 1 - naplneni testovaci (zdrojove) entity                                
        $this->testovaciZdrojovaEntity = new TestovaciEntity ( $this->identity );              
        $this->testovaciZdrojovaEntity->setCeleJmeno(      "BARNABÁŠ" . "KOSTKA" );        
        $this->testovaciZdrojovaEntity->setPrvekChar(      "CHARY *testovaci* " );
        $this->testovaciZdrojovaEntity->setPrvekVarchar(   "VARCHARY-testovaci-" );
        $this->testovaciZdrojovaEntity->setPrvekText(      "TEXTY/ testovaci /"); 
        $this->testovaciZdrojovaEntity->setPrvekInteger(   666 );
        $this->testovaciZdrojovaEntity->setPrvekBoolean(   \TRUE );
        $this->testovaciZdrojovaEntity->setPrvekDate(      $this->testDate ); 
        $this->testovaciZdrojovaEntity->setPrvekDatetime(  $this->testDateTime );                       
        $this->testovaciZdrojovaEntity->setPrvekTimestamp( $this->testDateTime );
        
        // 2 - jmena vlastností row objektu (pro nastavení filtru),
        // jejichž hodnoty   budou hydratovat entitu/se budou extrahovat  z entity.   
        $this->seznamJmen = [ "prvekVarchar" ,  "prvekChar" ,  "prvekText" , "prvekInteger" ,  "prvekBoolean" , 
                              "prvekDate", "prvekDatetime", "prvekTimestamp" ];                                             
    }
    
    
    /**
     * Hydratuji  objekt TestovaciEntity hodnotami z objektu TestovaciRowObject
     */     
    public function testOneToOneHydrate() : void {    
                                
        // 3 - filtr, nastaveni filtru, hydrator (filtr do hydratoru)  
        $oneToOneFilter = new OneToOneFilter($this->seznamJmen);     //vlastnosti rowobjectu!!!!!                   
        $oneToOneEntityHydrator = new OneToOneEntityHydrator( $oneToOneFilter );      
        
        // 4 -  hydratovani
        $novaPlnenaTestovaciEntity  =  new TestovaciEntity( $this->identity );           
        $oneToOneEntityHydrator->hydrate( $novaPlnenaTestovaciEntity, $this->testovaciZdrojovyRowObject );                
                
        // 5 - kontrola hydratace    
        $getMethodNameHydrator = new GetMethodNameHydrator();
        foreach (  $this->seznamJmen as  $value)  /* $value  je vlastnost rowobjectu!!!!!*/    { 
            $getMethodName = $getMethodNameHydrator->hydrateEntity($value);  //metoda entity
            // assertEquals (ocekavana, aktualni hodnota v entite)              
            $this->assertEquals( $this->testovaciZdrojovyRowObject->$value , $novaPlnenaTestovaciEntity->$getMethodName()  , "*CHYBA*");
         
        }                
        
        print_r ( "\n" );
        print_r( $novaPlnenaTestovaciEntity );
    }          
    
    
    
    
    
    /**
     * Extrahuji z objektu TestovaciEntity do objektu TestovaciRowObject.
     */     
    public function testOneToOneExtract(): void {     
                  
        // 3 - filtr, nastaveni filtru, hydrator (filtr do hydratoru)                                
        $oneToOneFilter = new OneToOneFilter(  $this->seznamJmen );     //vlastnosti rowobjectu!!!!!                
        $oneToOneEntityHydrator = new OneToOneEntityHydrator( $oneToOneFilter );      
        
        // 4 -  extrakce
        $novyPlnenyRowObject  =  new TestovaciRowObject ();           
        $oneToOneEntityHydrator->extract( $this->testovaciZdrojovaEntity, $novyPlnenyRowObject );        
                
        // 5 - kontrola extrakce     
        $getMethodNameHydrator = new GetMethodNameHydrator();
        foreach (  $this->seznamJmen as  $value )  /* $value je vlastnost rowobjectu!!!!!*/ {
             $getMethodName = $getMethodNameHydrator->hydrateEntity($value);  //metoda entity
            // assertEquals (ocekavana, aktualni hodnota v row objektu)                            
            $this->assertEquals( $this->testovaciZdrojovaEntity->$getMethodName() , $novyPlnenyRowObject->$value , "*CHYBA*");     
        }    
        
        print_r ( "\n" );
        print_r ( $novyPlnenyRowObject );
        
    }
}



//------------------------------------------------------------------------------------
class Identity implements IdentityInterface {
    /**
     *
     * @var bool Klíč je generovaný.
     */
    private $isGeneratedKey;       
    /**
     * Pole, které jako hodnoty má názvy(jména) polí částí klíče v asoc. poli keyHash.
     * @param array $attribute
     */
    private $attribute;    
    /**
     * Klíč - asoc.pole dvojic (KeyValue pair) jmeno(casti klice)->hodnota(casti klice).
     * @var array 
     */
    private $keyHash;
      
    public function __construct ( array $attribute, $isGeneratedKey=FALSE ) /*: IdentityInterface*/ {
        $this->isGeneratedKey = (bool) $isGeneratedKey;
        $this->attribute = $attribute;
//        $this->id = spl_object_hash($this);
//        $this->idMD5 = md5($this->id);
    }    
    public function isGenerated() : bool {
        return $this->isGeneratedKey;
    }       
    public function getKeyAttribute() {
        return $this->attribute;
    }
    public function getKeyHash() {
        return $this->keyHash;
    }     
    public function setKey( array $keyHash ) {        
        return $this;
    }
    public function isEqual( IdentityInterface $identity ) : bool {
        //$a == $b 	Equality 	TRUE if $a and $b have the same key/value pairs. - nezáleží na pořadí - testováno
        //$a === $b 	Identity 	TRUE if $a and $b have the same key/value pairs in the same order and of the same types.)
        return $this->keyHash == $identity->getKeyHash();
    }        
    public function hasEqualAttribute( IdentityInterface $identity ) : bool {
        return $this->attribute == $identity->getKeyAttribute();
    }
}



class TestovaciEntity  extends EntityAbstract implements EntityInterface   {    
    //private $uidPrimarniKlicZnaky ;  
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
        
//------------------------------------------------------
      
//    public function getUidPrimarniKlicZnaky() {
//        return $this->uidPrimarniKlicZnaky;
//    }
     
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
    
    public function setCeleJmeno( string $celeJmeno) :TestovaciEntity {
       $this->celeJmeno = $celeJmeno;
       return $this;
    }

    public function setPrvekVarchar($prvekVarchar) :TestovaciEntity {
        $this->prvekVarchar = $prvekVarchar;       
        return $this;        
    }

    public function setPrvekChar($prvekChar) :TestovaciEntity {
        $this->prvekChar = $prvekChar;
        return $this;
        
    }

    public function setPrvekText($prvekText) :TestovaciEntity {
        $this->prvekText = $prvekText;
        return $this;        
    }

    public function setPrvekInteger($prvekInteger) :TestovaciEntity{
        $this->prvekInteger = $prvekInteger;
        return $this;       
    }

    public function setPrvekDate(\DateTime $prvekDate) :TestovaciEntity{
        $this->prvekDate = $prvekDate;
        return $this;        
    }

    public function setPrvekDatetime(\DateTime $prvekDatetime) :TestovaciEntity {
        $this->prvekDatetime = $prvekDatetime;
        return $this;        
    }

    public function setPrvekTimestamp(\DateTime $prvekTimestamp) :TestovaciEntity {
        $this->prvekTimestamp = $prvekTimestamp;
        return $this;      
    }

    public function setPrvekBoolean($prvekBoolean) :TestovaciEntity{
        $this->prvekBoolean = $prvekBoolean;
        return $this;
    }
}

    
/**
 * ...Row je 'prepravka na data' plain data object
 *
 * @author vlse2610
 */
class TestovaciRowObject implements RowObjectInterface {              
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

