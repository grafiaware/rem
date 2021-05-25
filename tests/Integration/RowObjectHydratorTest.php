<?php
use PHPUnit\Framework\TestCase;

use Pes\Database\Metadata\MetadataProviderMysql;

use Model\RowObject\RowObjectInterface;

use Model\RowObject\Hydrator\RowObjectHydrator;
use Model\RowObject\Hydrator\NameHydrator;
use Model\RowObject\Hydrator\Exception;


/**
 * objekt -  jen pro ucel testu
 * ...Row je 'prepravka na data' plain data object
 *
 * @author vlse2610
 */
class TestovaciTableRowObject implements RowObjectInterface {      
    
    public $uidPrimarniKlicZnaky;         
    public $prvekVarchar;
    public $prvekChar;
    public $prvekText;
    public $prvekInteger;    
    /**
     *
     * @var \DateTime 
     */
    public $prvekDate;
    /**
     *
     * @var \DateTime 
     */
    public $prvekDatetime;
    /**
     *
     * @var \DateTime 
     */
    public $prvekTimestamp;    
    public $prvekBoolean;        
        
}
//----------------------------------------------------------------------------------
//----------------------------------------------------------------------------------


/**
 * Description of ...Test
 *
 *
 */
class RowObjectHydratorTest extends TestCase {
    const DB_NAME = 'tester_3_test';
    const DB_HOST = 'localhost';
    const USER = 'root';
    const PASS = 'spravce';
    
    const HODNOTA_UID_A = "AAA _test_hydrate";
    const HODNOTA_VARCHAR = "VARCHARY varchary _test";
    const HODNOTA_CHAR = "CHARY chary _test";
    const HODNOTA_TEXT = "TEXTY texty _test";
    const HODNOTA_INTEGER =  666;    
    const HODNOTA_BOOLEAN = \TRUE;
    
    const HODNOTA_UID_B = "BBB _test_extract";    

    
    private $titulPred;
    private $jmeno;
    private $prijmeni;
    private $titulZa;
    
    protected $testDateString ;
    protected $testDate ;
    protected $testDateTimeString;
    protected $testDateTime;
    
    protected $pole_hodnot_sloupcu;
    protected $poleHodnotObjektu;                                 
                  
    
    /**
     * Pred každým testem.
     * @return void
     */
    public function setUp(): void {               
        $this->testDateString = "2010-11-12";
        $this->testDate = DateTime::createFromFormat("Y-m-d", $this->testDateString)->setTime(0,0,0,0); // s "0:0:0:0" je false
        $this->testDateTimeString = "2005-06-07 22:23:24";
        $this->testDateTime = DateTime::createFromFormat("Y-m-d H:i:s", $this->testDateTimeString);
        //----------------------
        $this->pole_hodnot_sloupcu = [
                "uid_primarni_klic_znaky" => self::HODNOTA_UID_A, 
                "prvek_varchar" => self::HODNOTA_VARCHAR, 
                "prvek_char" => self::HODNOTA_CHAR, 
                "prvek_text" => self::HODNOTA_TEXT, 
                "prvek_integer" => self::HODNOTA_INTEGER,               
                "prvek_date" => $this->testDateString,          // string   
                "prvek_datetime" => $this->testDateTimeString,  // string  
                "prvek_timestamp" => $this->testDateTimeString, // string  
                "prvek_boolean" => self::HODNOTA_BOOLEAN
                ];                
        
        $this->poleHodnotObjektu = [
                "uidPrimarniKlicZnaky" => self::HODNOTA_UID_A, 
                "prvekVarchar" => self::HODNOTA_VARCHAR, 
                "prvekChar" => self::HODNOTA_CHAR, 
                "prvekText" => self::HODNOTA_TEXT, 
                "prvekInteger" => self::HODNOTA_INTEGER,    
            
                "prvekDate" => $this->testDate,        // objekt       
                "prvekDatetime" => $this->testDateTime,    // objekt 
                "prvekTimestamp" => $this->testDateTime,  // objekt 
                "prvekBoolean" => self::HODNOTA_BOOLEAN  
                ];   
        //----------------------                    
        //smazat  tabulku drop table,
        //VYROBIT TABULKU pomoci PDO, jen strukturu  -- potrebuji table metadata do rowObjectHydratoru                      
        $dsn = 'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME .';';
        $dbh = new \PDO($dsn, self::USER, self::PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));               
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //aby PDO vyhazovalo exceptions     
        
        $n = $dbh->exec('DROP table IF EXISTS testovaci_table');  // pozn. exec vraci:  false = chyba,  0-n pocet dotcenych
              
        $strsql = "CREATE TABLE `testovaci_table` (
          `uid_primarni_klic_znaky` varchar(50) COLLATE utf8_czech_ci NOT NULL,
          `prvek_varchar` varchar(1024) COLLATE utf8_czech_ci DEFAULT NULL,
          `prvek_char` char(255) COLLATE utf8_czech_ci DEFAULT NULL,
          `prvek_text` text COLLATE utf8_czech_ci,
          `prvek_integer` int(11) DEFAULT NULL,
          
          `prvek_date` date DEFAULT NULL,
          `prvek_datetime` datetime DEFAULT NULL,
          `prvek_timestamp` timestamp NULL DEFAULT NULL,
          `prvek_boolean` tinyint(1) DEFAULT NULL,
          PRIMARY KEY (`uid_primarni_klic_znaky`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci";
        $n = $dbh->exec($strsql);  
        
        // do tabulky zapsat testovaci větu
        $strsql = "INSERT INTO `testovaci_table` ( " 
                . implode(', ', array_keys($this->pole_hodnot_sloupcu) ) . ") "         
                . "VALUES (" .(count($this->pole_hodnot_sloupcu) ? ":" : "").implode(', :', array_keys($this->pole_hodnot_sloupcu)) . ") ";
        $stmt = $dbh->prepare($strsql);
        foreach ($this->pole_hodnot_sloupcu as $key => $hodnotaSloupce) {
                $stmt->bindValue(":".$key, $hodnotaSloupce);
        }
        $succ = $stmt->execute();              
    }
    
   
    public static function setUpBeforeClass(): void
    {
//        self::$container = (new TestDaoContainerConfigurator())->configure(new Container());
//        self::$dbhZContaineru  = self::$container->get(Handler::class); // $dbh = $container->get(Handler::class); 
    }
    public static function tearDownAfterClass(): void
    {
//         self::$container = null;
//         self::$dbhZContaineru  = null;
    }
//------------------------------------------------------------------------------------------------
   
    
    /**
     * Hydratuji z pole $row ("db dataset") do TestovaciTableRowObjectu $objektRow.
     */
    public function testHydrate() : void {      
        $dsn = 'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME .';';
        $dbh = new \PDO($dsn, self::USER, self::PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));    
        $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $metaDataProvider = new MetadataProviderMysql( $dbh  );        
        $rowObjectHydrator = new RowObjectHydrator( new NameHydrator(), $metaDataProvider->getTableMetadata('testovaci_table'));              
   
        //### TEST HYDRATACE Z POLE HODNOT SLOUPCU  ----------------------------------------------------                                     
        // hydratuji objekt  $objektRow   //  typ se bere podle sloupce tabulky v db
        $objektRow  =  new TestovaciTableRowObject();
        $rowObjectHydrator->hydrate( $objektRow, $this->pole_hodnot_sloupcu);             
        //(expected poleHodnotObjektu, actual )
        //$objektRow->prvekDate !!obsahuje nespravne|spatne hodnoty casu! nesmyslne!nepotrebne                
        $this->assertEquals( $this->poleHodnotObjektu["uidPrimarniKlicZnaky"], $objektRow->uidPrimarniKlicZnaky, "CHYBA" );       
        $this->assertEquals( $this->poleHodnotObjektu["prvekVarchar"],$objektRow->prvekVarchar, "CHYBA" );
        $this->assertEquals( $this->poleHodnotObjektu["prvekChar"], $objektRow->prvekChar, "CHYBA" );
        $this->assertEquals( $this->poleHodnotObjektu["prvekText"], $objektRow->prvekText, "CHYBA" );
        $this->assertEquals( $this->poleHodnotObjektu["prvekInteger"], $objektRow->prvekInteger, "CHYBA" );    
        $this->assertEquals( $this->poleHodnotObjektu["prvekDate"]->getTimestamp(), $objektRow->prvekDate->getTimestamp()/*format("Y-m-d")*/, "CHYBA" );          
        $this->assertEquals( $this->poleHodnotObjektu["prvekDatetime"]->getTimeStamp(), $objektRow->prvekDatetime->getTimeStamp()/*format("Y-m-d H:i:s")*/, "CHYBA" );
        $this->assertEquals( $this->poleHodnotObjektu["prvekTimestamp"]->getTimeStamp(), $objektRow->prvekTimestamp->getTimeStamp()/*format("Y-m-d H:i:s")*/, "CHYBA" );        
        $this->assertEquals( $this->poleHodnotObjektu["prvekBoolean"], $objektRow->prvekBoolean, "CHYBA" );  
        
        
        //### TEST HYDRATACE Z POLE sloupcu --- SAME NULLy ----------------------------------------------------
        foreach ($this->pole_hodnot_sloupcu as $k => $value) {
            $this->pole_hodnot_sloupcu[$k ] = null;                    
        }        
        // hydratuji objekt $objektRow   
        $objektRow  =  new TestovaciTableRowObject();
        $rowObjectHydrator->hydrate( $objektRow, $this->pole_hodnot_sloupcu);                      
        //(expected poleHodnotObjektu , actual)
        $this->assertEquals( null, $objektRow->uidPrimarniKlicZnaky, "CHYBA" );
        $this->assertEquals( null, $objektRow->prvekChar, "CHYBA" );
        $this->assertEquals( null, $objektRow->prvekVarchar, "CHYBA" );
        $this->assertEquals( null, $objektRow->prvekText, "CHYBA" );
        $this->assertEquals( null, $objektRow->prvekInteger, "CHYBA" );
        $this->assertEquals( null, $objektRow->prvekDate, "CHYBA" );
        $this->assertEquals( null, $objektRow->prvekDatetime, "CHYBA" );
        $this->assertEquals( null, $objektRow->prvekTimestamp, "CHYBA" );    
        $this->assertEquals( null, $objektRow->prvekBoolean, "CHYBA" );          
        
        //### TEST HYDRATACE Z DATABAZE   ----------------------------------------------------
        $strsql = "SELECT * FROM testovaci_table" ;
        $stmt = $dbh->query($strsql);
        $stmt->execute();
        $radek = $stmt->fetch(PDO::FETCH_ASSOC);    //both 
        $objektRow  =  new TestovaciTableRowObject();
        $rowObjectHydrator->hydrate( $objektRow, $radek );   
        /* testy */  
        //(expected poleHodnotObjektu, actual )
        //$objektRow->prvekDate !!nespravne|spatne hodnoty casu! nesmyslne!nepotrebne !!  osetreno v hydratoru na 0:0:0:0               
        $this->assertEquals( $this->poleHodnotObjektu["uidPrimarniKlicZnaky"], $objektRow->uidPrimarniKlicZnaky, "CHYBA" );       
        $this->assertEquals( $this->poleHodnotObjektu["prvekVarchar"],$objektRow->prvekVarchar, "CHYBA" );
        $this->assertEquals( $this->poleHodnotObjektu["prvekChar"], $objektRow->prvekChar, "CHYBA" );
        $this->assertEquals( $this->poleHodnotObjektu["prvekText"], $objektRow->prvekText, "CHYBA" );
        $this->assertEquals( $this->poleHodnotObjektu["prvekInteger"], $objektRow->prvekInteger, "CHYBA" );    
        $this->assertEquals( $this->poleHodnotObjektu["prvekDate"]->getTimestamp(), $objektRow->prvekDate->getTimestamp(), "CHYBA" );           /*->format("Y-m-d")*/   
        $this->assertEquals( $this->poleHodnotObjektu["prvekDatetime"]->getTimeStamp(), $objektRow->prvekDatetime->getTimeStamp(), "CHYBA" );   /*->format("Y-m-d H:i:s")*/
        $this->assertEquals( $this->poleHodnotObjektu["prvekTimestamp"]->getTimeStamp(), $objektRow->prvekTimestamp->getTimeStamp(), "CHYBA" ); /*->format("Y-m-d H:i:s")*/     
        $this->assertEquals( $this->poleHodnotObjektu["prvekBoolean"], $objektRow->prvekBoolean, "CHYBA" );    
   
    }
    
    
    /**
     * Extrahuji z TestaovaciTableRowObjectu $oRow  do pole $row (db dataset).
     */
    public function testExtractDoPoleRow(): void {   
        $dsn = 'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME .';';
        $dbh = new \PDO($dsn, self::USER, self::PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));  
        $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $metaDataProvider = new MetadataProviderMysql ($dbh);
        $rowObjectHydrator = new RowObjectHydrator (new NameHydrator(), $metaDataProvider->getTableMetadata('testovaci_table'));  
        
        //### TEST EXTRAKTu Z HODNOT OBJEKTU do pole sloupcu ----------------------------------------------------
        $objektRow = new TestovaciTableRowObject();  //pripravim si objekt
        foreach ($this->poleHodnotObjektu as $key => $value) { //pripravim si objekt
            $objektRow->$key = $value; 
        }    
        
        //extrahuji objekt $oRow 
        $row = [];           
        $rowObjectHydrator->extract( $objektRow, $row );  //naplnuje pole aloupcu $row      
        
        $this->assertIsNumeric($row["prvek_integer"], "CHYBA"  );  
        $this->assertIsBool($row["prvek_boolean"],"CHYBA" );
        //(expected pole_hodnot_sloupcu, actual)
        $this->assertEquals( $this->pole_hodnot_sloupcu["uid_primarni_klic_znaky"], $row["uid_primarni_klic_znaky"], "CHYBA");        
        $this->assertEquals( $this->pole_hodnot_sloupcu["prvek_varchar"], $row["prvek_varchar"], "CHYBA" );
        $this->assertEquals( $this->pole_hodnot_sloupcu["prvek_char"], $row["prvek_char"], "CHYBA" );        
        $this->assertEquals( $this->pole_hodnot_sloupcu["prvek_text"], $row["prvek_text"], "CHYBA" );       
        $this->assertEquals( $this->pole_hodnot_sloupcu["prvek_integer"], $row["prvek_integer"], "CHYBA" );       
        $this->assertEquals( $this->pole_hodnot_sloupcu["prvek_date"] , $row["prvek_date"], "CHYBA" );  //
        $this->assertEquals( $this->pole_hodnot_sloupcu["prvek_datetime"], $row["prvek_datetime"], "CHYBA" );
        $this->assertEquals( $this->pole_hodnot_sloupcu["prvek_timestamp"], $row["prvek_timestamp"], "CHYBA" );   
        $this->assertEquals( $this->pole_hodnot_sloupcu["prvek_boolean"], $row["prvek_boolean"], "CHYBA" );     
          
        
        
         //### TEST EXTRAKTu Z  HODNOT OBJEKTU null do pole sloupcu (dataset) ----------------------------------------------------
        foreach ($this->poleHodnotObjektu as $key => $value) { //pripravim si objekt
            $objektRow->$key = null; 
        }                                
        //extrahuji objekt $objektRow do pole sloupcu $row
        $row = [];           
        $rowObjectHydrator->extract( $objektRow, $row );  //naplnuje pole $row        
        // (actual)
        $this->assertNull(  $row["uid_primarni_klic_znaky"] , "CHYBA");       
        $this->assertNull(  $row["prvek_char"], "CHYBA" );
        $this->assertNull(  $row["prvek_varchar"], "CHYBA" );
        $this->assertNull(  $row["prvek_text"], "CHYBA" );
        $this->assertNull(  $row["prvek_integer"], "CHYBA" );
        $this->assertNull(  $row["prvek_date"], "CHYBA" );
        $this->assertNull(  $row["prvek_datetime"], "CHYBA" );
        $this->assertNull(  $row["prvek_timestamp"], "CHYBA" );     
        $this->assertNull(  $row["prvek_boolean"], "CHYBA" );
        
    }    
        
    /**
     * Extrahuji z TestaovaciTableRowObjectu $oRow do pole $row (db dataset) a dale zapis do databaze.
     */
    public function testExtractDoDB() : void {   
        $dsn = 'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME .';';
        $dbh = new \PDO($dsn, self::USER, self::PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));  
        $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $metaDataProvider = new MetadataProviderMysql ($dbh);
        $rowObjectHydrator = new RowObjectHydrator (new NameHydrator(), $metaDataProvider->getTableMetadata('testovaci_table'));              
        
        //### TEST EXTRAKTu Z  HODNOT OBJEKTU do pole hodnot a dale do DB ----------------------------------------------------
        //a znovu precist a porovnat prectene s puvodnim objektem ----------------------------------------------------
        $objektRow = new TestovaciTableRowObject();  //pripravim si objekt
        foreach ($this->poleHodnotObjektu as $key => $value) { //pripravim si objekt
            $objektRow->$key = $value; 
        }   
        $objektRow->uidPrimarniKlicZnaky =  self::HODNOTA_UID_B;        
        $row = [];           
        $rowObjectHydrator->extract( $objektRow, $row ); 
        
         // do tabulky zapsat testovaci větu
        $strsql = "INSERT INTO `testovaci_table` ( "      
                . implode(', ', array_keys($row) ) . ") "          
                . "VALUES (" . (count($row) ? ":" : "") . implode(', :', array_keys($row)) . ") ";
        $stmt = $dbh->prepare($strsql);
        foreach ($row as $key => $hodnotaSloupce) {
                $b = $stmt->bindValue(":".$key, $hodnotaSloupce);
        }
        $succ = $stmt->execute(); 
        
        // cteni zpet
        $strsql = "SELECT * FROM testovaci_table WHERE uid_primarni_klic_znaky = '" . self::HODNOTA_UID_B . "'";
        $stmt = $dbh->query($strsql);
        $stmt->execute();
        $radek = $stmt->fetch(PDO::FETCH_ASSOC);    
        //$objektRow  =  new TestovaciTableRow();
             
        $this->assertIsNumeric( $radek["prvek_integer"], "CHYBA"  );          
        //(expected pole_hodnot_sloupcu, actual - prectene z db )
        $this->assertEquals( self::HODNOTA_UID_B , $radek["uid_primarni_klic_znaky"], "CHYBA");        
        $this->assertEquals( $this->pole_hodnot_sloupcu["prvek_varchar"], $radek["prvek_varchar"], "CHYBA" );
        $this->assertEquals( $this->pole_hodnot_sloupcu["prvek_char"], $radek["prvek_char"], "CHYBA" );        
        $this->assertEquals( $this->pole_hodnot_sloupcu["prvek_text"], $radek["prvek_text"], "CHYBA" );       
        $this->assertEquals( $this->pole_hodnot_sloupcu["prvek_integer"], $radek["prvek_integer"], "CHYBA" );       
        $this->assertEquals( $this->pole_hodnot_sloupcu["prvek_date"] , $radek["prvek_date"], "CHYBA" );  //
        $this->assertEquals( $this->pole_hodnot_sloupcu["prvek_datetime"], $radek["prvek_datetime"], "CHYBA" );
        $this->assertEquals( $this->pole_hodnot_sloupcu["prvek_timestamp"], $radek["prvek_timestamp"], "CHYBA" );    
        $this->assertEquals( $this->pole_hodnot_sloupcu["prvek_boolean"], $radek["prvek_boolean"], "CHYBA" );   
               
    }
    
    
  
    
    
    /**
     * Vyhození výjimky pro neznámé jméno v poli datasetu.   
     * Hydratovano pres sloupce tabulky - zachyceno Exception\UndefinedColumnNameException - "Neznámé (neex.) jméno sloupce"
     */
    public function testHydrate_UndefinedColumnNameException() {
    //* Nectu z db, nastavim  v $objektRow jméno neznámé vlastnosti. Tento sloupec nenalezen v TableMetadata.         
                                
        $dsn = 'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME .';';
        $dbh = new \PDO($dsn, self::USER, self::PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));    
        $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);                                 
        
        $objektRow  =  new TestovaciTableRowObject();
        $objektRow->prvekNavic = "XXXXX"; 
        
        $metaDataProvider = new MetadataProviderMysql( $dbh );         
        try {                  
            $rowObjectHydrator = new RowObjectHydrator( new NameHydrator(), $metaDataProvider->getTableMetadata('testovaci_table'));              
            //### TEST HYDRATACE Z POLE HODNOT SLOUPCU  ----------------------------------------------------                                     
            // hydratuji objekt  $objektRow  polem $this->pole_hodnot_sloupcu  //  typ se bere podle sloupce tabulky v db            
            $rowObjectHydrator->hydrate($objektRow, $this->pole_hodnot_sloupcu);        // Vyhodí výjimku     
            
        } catch ( Exception\UndefinedColumnNameException $v) {
            $this->assertStringStartsWith('Neznámé (neex.) jméno sloupce', $v->getMessage(), "CHYBA");
        }
    }
   
    
    /**
     * Vyhození výjimky pro nekonvertovatelny obsah prvku pro "typ date" v poli datasetu.   
     * Hydratovano pres sloupce tabulky - zachyceno Exception\DatetimeConversionFailureException - "Hodnota typu date ze sloupce"
     */       
    public function testHydrate_DatetimeConversionFailureException_Date() {
    //* Nectu z db,                    
        $dsn = 'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME .';';
        $dbh = new \PDO($dsn, self::USER, self::PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));    
        $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);                                 
        
        $objektRow  =  new TestovaciTableRowObject();  
        /**/   $this->pole_hodnot_sloupcu['prvek_date'] = 'nesmysl';      /**/          
        $metaDataProvider = new MetadataProviderMysql( $dbh );         
        try {                  
            $rowObjectHydrator = new RowObjectHydrator( new NameHydrator(), $metaDataProvider->getTableMetadata('testovaci_table'));              
            //### TEST HYDRATACE Z POLE HODNOT SLOUPCU  ----------------------------------------------------                                     
            // hydratuji objekt  $objektRow  polem $this->pole_hodnot_sloupcu  //  typ se bere podle sloupce tabulky v db
                                 
            $rowObjectHydrator->hydrate($objektRow, $this->pole_hodnot_sloupcu);        // Vyhodí výjimku      
        } catch (Exception\DatetimeConversionFailureException $v) {
            $this->assertStringStartsWith('Hodnota typu date ze sloupce', $v->getMessage(), "CHYBA");
        }
    }
    
    
    
    /**
     * Vyhození výjimky pro nekonvertovatelny obsah prvku pro "typ datetime" v poli datasetu.   
     * Hydratovano pres sloupce tabulky - zachyceno Exception\DatetimeConversionFailureException - "Hodnota typu date ze sloupce"
     */       
    public function testHydrate_DatetimeConversionFailureException_Datetime() {
    //* Nectu z db,                    
        $dsn = 'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME .';';
        $dbh = new \PDO($dsn, self::USER, self::PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));    
        $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);                                 
        
        $objektRow  =  new TestovaciTableRowObject(); 
        /**/   $this->pole_hodnot_sloupcu['prvek_datetime'] = 'nesmysl';      /**/          
        $metaDataProvider = new MetadataProviderMysql( $dbh );         
        try {                  
            $rowObjectHydrator = new RowObjectHydrator( new NameHydrator(), $metaDataProvider->getTableMetadata('testovaci_table'));              
            //### TEST HYDRATACE Z POLE HODNOT SLOUPCU  ----------------------------------------------------                                     
            // hydratuji objekt  $objektRow  polem $this->pole_hodnot_sloupcu  //  typ se bere podle sloupce tabulky v db                                  
            $rowObjectHydrator->hydrate($objektRow, $this->pole_hodnot_sloupcu);        // Vyhodí výjimku      
        } catch (Exception\DatetimeConversionFailureException $v) {
            $this->assertStringStartsWith('Hodnota typu datetime ze sloupce', $v->getMessage(), "CHYBA");
        }
    }
        
    
    /**
     * Vyhození výjimky když název vlastnosti objektuRow není v tabulce db. (směr z TestovaciTableRowObjectu $oRow  do pole $row (db dataset))
     * Extraktovano pres vlastnosti objektu - zachyceno Exception\UndefinedColumnNameException - "Název sloupce"  "není v tabulce"     
     */
    public function testExtract_UndefinedColumnNameException () {   
        $dsn = 'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME .';';
        $dbh = new \PDO($dsn, self::USER, self::PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));  
        $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $metaDataProvider = new MetadataProviderMysql ($dbh);
        
        //### TEST EXTRAKTu Z HODNOT OBJEKTU do pole sloupcu ----------------------------------------------------
        $objektRow = new TestovaciTableRowObject();  //pripravim si objekt        
        foreach ($this->poleHodnotObjektu as $key => $value) { //pripravim si objekt
            $objektRow->$key = $value; 
        }    
        /**/  $objektRow->neznamaVlastnost = "cokoli";     /**/

        $row = [];   
        try {          
            $rowObjectHydrator = new RowObjectHydrator (new NameHydrator(), $metaDataProvider->getTableMetadata('testovaci_table'));  
            $rowObjectHydrator->extract( $objektRow, $row );  // Vyhodí výjimku   //naplnuje pole sloupcu $row      
        }
         catch (Exception\UndefinedColumnNameException $v) {
            $this->assertStringStartsWith( "Název sloupce" , $v->getMessage(), "CHYBA");
            $this->assertStringContainsString (  "není v tabulce" , $v->getMessage(), "CHYBA");
        }        
    }
    
    
    /** 
     * Vyhození výjimky pri konverzi - pro  vlastnost typu objekt Datetime -  do pole datasetu $row.   
     * (směr z TestovaciTableRowObjectu $oRow  do pole $row (db dataset))
     * Extrahovano přes vlastnosti objektu - zachyceno Exception\DatetimeConversionFailureException - "Typ sloupce"  "není instancí objektu"     
     */
    public function testExtract_DatetimeConversionFailureException_Datetime () {   
        $dsn = 'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME .';';
        $dbh = new \PDO($dsn, self::USER, self::PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));  
        $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $metaDataProvider = new MetadataProviderMysql ($dbh);
        
        //### TEST EXTRAKTu Z HODNOT OBJEKTU do pole sloupcu ----------------------------------------------------
        $objektRow = new TestovaciTableRowObject();  //pripravim si objekt  
        foreach ($this->poleHodnotObjektu as $key => $value) { //pripravim si objekt
            $objektRow->$key = $value; 
        }    
        /**/    $objektRow->prvekDatetime = "neniObjektDatetime";     /**/ 
        $row = [];   
        try {        
            $rowObjectHydrator = new RowObjectHydrator (new NameHydrator(), $metaDataProvider->getTableMetadata('testovaci_table'));  
            $rowObjectHydrator->extract( $objektRow, $row );  // Vyhodí výjimku   //naplnuje pole sloupcu $row      
        } catch (Exception\DatetimeConversionFailureException $v) {
            $this->assertStringStartsWith( "Typ sloupce" , $v->getMessage(), "CHYBA");
            $this->assertStringContainsString ( "není instancí objektu" , $v->getMessage(), "CHYBA");
        }        
    }
    
    
    
    /** 
     * Vyhození výjimky pri konverzi - pro  vlastnost typu objekt Datetime -  do pole datasetu $row.   
     * (směr z TestovaciTableRowObjectu $oRow  do pole $row (db dataset))
     * Extrahovano přes vlastnosti objektu - zachyceno Exception\DatetimeConversionFailureException - "Typ sloupce"  "není instancí objektu"     
     */
    public function testExtract_DatetimeConversionFailureException_Date () {   
        $dsn = 'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME .';';
        $dbh = new \PDO($dsn, self::USER, self::PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));  
        $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $metaDataProvider = new MetadataProviderMysql ($dbh);
        
        //### TEST EXTRAKTu Z HODNOT OBJEKTU do pole sloupcu ----------------------------------------------------
        $objektRow = new TestovaciTableRowObject();  //pripravim si objekt  
        foreach ($this->poleHodnotObjektu as $key => $value) { //pripravim si objekt
            $objektRow->$key = $value; 
        }    
        /**/    $objektRow->prvekDate = "nejsemObjektDatetime";     /**/ 
        $row = [];   
        try {          
            $rowObjectHydrator = new RowObjectHydrator (new NameHydrator(), $metaDataProvider->getTableMetadata('testovaci_table'));  
            $rowObjectHydrator->extract( $objektRow, $row );  // Vyhodí výjimku   //naplnuje pole sloupcu $row      
        } catch (Exception\DatetimeConversionFailureException $v) {
            $this->assertStringStartsWith( "Typ sloupce" , $v->getMessage(), "CHYBA");
            $this->assertStringContainsString ( "není instancí objektu" , $v->getMessage(), "CHYBA");
        }        
    }
    
    
    
}    

    //nepotrebujeme  pridavat sloupec , protoze sloupce navic nevadi            
//  /**
//     * Vyhození výjimky pro neznámé jméno sloupce v db. /pozn. v db pridan neznamy sloupec/.    
//     * hydratovano pres sloupce tabulky - zachyceno  Exception\UnknownPropertyNameException - "Neznámé (neex.) jméno vlastnosti"
//     */
//    public function testHydrate_UnknownPropertyNameException_databaze() {          
//    // Ctu z db , tj. - do db pridam sloupec a ctu.                
//        $dsn = 'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME .';';
//        $dbh = new \PDO($dsn, self::USER, self::PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));    
//        $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);       
//                 
//        /**/    $strsql = "ALTER TABLE testovaci_table " .
//                  "ADD COLUMN pole_navic_db varchar(1024) COLLATE utf8_czech_ci DEFAULT NULL";
//        $n = $dbh->exec($strsql);                                  /**/      
//        $strsql = "SELECT * FROM testovaci_table WHERE uid_primarni_klic_znaky = '" . self::HODNOTA_UID_A . "'";
//        $stmt = $dbh->query($strsql);
//        $stmt->execute();
//        $radekPoAlter = $stmt->fetch(PDO::FETCH_ASSOC);   
//        
//        $objektRow  =  new TestovaciTableRow();
//        $metaDataProvider = new MetadataProviderMysql( $dbh );         
//        try {                  
//            $rowObjectHydrator = new RowObjectHydrator( new NameHydrator(), $metaDataProvider->getTableMetadata('testovaci_table'));              
//            //### TEST HYDRATACE Z POLE HODNOT SLOUPCU  ----------------------------------------------------                                     
//            // hydratuji objekt  $objektRow  radkemPoAlter (radek precteny z db po pridanem sloupci) //  typ se bere podle sloupce tabulky v db
//            
//            $rowObjectHydrator->hydrate($objektRow, $radekPoAlter);        // Vyhodí výjimku                  
//        } catch (Exception\UnknownPropertyNameException $v) {
//            $this->assertStringStartsWith('Neznámé (neex.) jméno vlastnosti', $v->getMessage(), "CHYBA");
//        }
//    }


    //nepotrebujeme odnastavovat vlastnost - nesmyslny test - odnastavena vlestnost neni treba naplnovat
//    /**
//     * Vyhození výjimky pro neznámé jméno sloupce v db. /pozn. v objektu odnastavena vlastnost/.    
//     * hydratovano pres sloupce tabulky - zachyceno  Exception\UnknownPropertyNameException - "Neznámé (neex.) jméno vlastnosti"
//     */
//    public function testHydrate_UnknownPropertyNameException_objekt() {          
//    // Ctu z db , tj. - do db pridam sloupec a ctu.                
//        $dsn = 'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME .';';
//        $dbh = new \PDO($dsn, self::USER, self::PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));    
//        $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);       
//        
//        $strsql = "SELECT * FROM testovaci_table WHERE uid_primarni_klic_znaky = '" . self::HODNOTA_UID_A . "'";
//        $stmt = $dbh->query($strsql);
//        $stmt->execute();
//        $radek = $stmt->fetch(PDO::FETCH_ASSOC);   
//        
//        $objektRow  =  new TestovaciTableRow();
//        /**/    unset($objektRow->prvekChar);   /**/
//        $metaDataProvider = new MetadataProviderMysql( $dbh );         
//        try {                  
//            $rowObjectHydrator = new RowObjectHydrator( new NameHydrator(), $metaDataProvider->getTableMetadata('testovaci_table'));              
//            //### TEST HYDRATACE Z POLE HODNOT SLOUPCU  ----------------------------------------------------                                     
//            // hydratuji objekt  $objektRow  radkemPoAlter (radek precteny z db po pridanem sloupci) //  typ se bere podle sloupce tabulky v db                     
//            $rowObjectHydrator->hydrate($objektRow, $radek);        // Vyhodí výjimku                  
//        } catch (Exception\UnknownPropertyNameException $v) {
//            $this->assertStringStartsWith('Neznámé (neex.) jméno vlastnosti', $v->getMessage(), "CHYBA");
//        }
//    }


    
    
    

//        $this->hodnotaDateString = (new \DateTime())->setDate(2019,12,21)->setTime(0,0,0)->format("Y-m-d H:i:s"); //string jako datetime,ale s nulama
//        $this->hodnotaDateTimeString = (new \DateTime())->setDate(2019,12,21)->setTime(21,55,55)->format("Y-m-d H:i:s");   
//        $this->hodnotaDate = (new \DateTime())->setDate(2019,12,21)->setTime(0,0,0);
//        $this->hodnotaDateTime = (new \DateTime())->setDate(2019,12,21)->setTime(21,55,55);   

//                                            new \PDO(
//                                            'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME .';', 
//                                            self::USER, 
//                                            self::PASS, 
//                                            [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'']  )
  