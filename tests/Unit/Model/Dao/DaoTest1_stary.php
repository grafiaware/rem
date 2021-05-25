<?php
use PHPUnit\Framework\TestCase;

// kontejner
use Test\TestDaoContainerConfigurator;
use Pes\Container\Container;
// database
use Pes\Database\Handler\Handler;
use Pes\Database\Metadata\MetadataProviderMysql;

use Model\Dao\TestovaciTableDao;
use Model\RowObject\Hydrator\RowObjectHydrator;
use Model\RowObject\Hydrator\NameHydrator;
use Model\RowObject\TestovaciTableRow;

//use Model\RowObject\RowObjectInterface;
//use Model\RowObject\KonfiguraceTestuRow;

/**
 * Description of DaoTest
 *
 * @author pes2704
 */
class DaoTest extends TestCase {
    const DB_NAME = 'tester_3_test';
    const DB_HOST = 'localhost';
    const USER = 'root';
    const PASS = 'spravce';
    
    const  HODNOTA_UID_1 = "AAA _test";
    const  HODNOTA_VARCHAR = "VARCHARY _test";
    const  HODNOTA_CHAR = "CHARY _test";
    const  HODNOTA_TEXT = "TEXTY texty _test";
    const  HODNOTA_INTEGER =  666;
    
    const  HODNOTA_UID_2 = "BBB _test";    
    
    protected static $hodnotaDate;
    protected static $hodnotaDateTime;
    
    protected static  $dbhZContaineru;
    protected static  $container;
                  
    

    public function setUp(): void {
        self::$hodnotaDate = (new \DateTime())->format("Y-m-d");
        self::$hodnotaDateTime = (new \DateTime())->format("Y-m-d H:i:s"); 
        //(new \DateTime())->getTimestamp() . ' )' ; --php
        
        //vymaže tabulku, zapíše 1 ř. v UTF8 
        $dsn = 'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME .';';
        $dbh = new \PDO($dsn,   self::USER, self::PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));                          
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //aby PDO vyhazovalo exceptions     
        
        $n = $dbh->exec('DROP table IF EXISTS testovaci_table');  // pozn. exec vraci:  false = chyba,  0-n pocet dotcenych
        //$dbh->exec('DELETE FROM testovaci_table');
               
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
        
               
        $strsql = 'INSERT INTO testovaci_table ' .
                '(uid_primarni_klic_znaky, ' .
                'prvek_varchar, ' . 'prvek_char, ' . 'prvek_text, ' . 'prvek_integer, ' .
                'prvek_date, ' .
                'prvek_datetime, ' . 
                'prvek_timestamp ' .
                ') ' .                
                'VALUES (' .
                '"' . self::HODNOTA_UID_1     . '",' .
                '"' . self::HODNOTA_VARCHAR   . '",' .  
                '"' . self::HODNOTA_CHAR    . '",' .
                '"' . self::HODNOTA_TEXT    . '",' .
                ' ' . self::HODNOTA_INTEGER . ', ' .
                '"' . self::$hodnotaDate      . '", ' . 
                '"' . self::$hodnotaDateTime  . '",  ' .
                'now()  )' ;   
                
        $n = $dbh->exec( $strsql );               
        
        $strsql = 'INSERT INTO testovaci_table ' .
                '(uid_primarni_klic_znaky, ' .
                'prvek_varchar, ' . 'prvek_char, ' . 'prvek_text, ' . 'prvek_integer, ' .
                'prvek_date, ' .
                'prvek_datetime, ' . 
                'prvek_timestamp ' .
                ') ' .                
                'VALUES (' .
                '"'   . self::HODNOTA_UID_2   . '",' .
                'null,null,null,null,null,null,null )' ; 
         $n = $dbh->exec( $strsql );            
         
         
         
         
        
    }
    
   
    public static function setUpBeforeClass(): void
    {
        self::$container = (new TestDaoContainerConfigurator())->configure(new Container());
        self::$dbhZContaineru  = self::$container->get(Handler::class); // $dbh = $container->get(Handler::class); 
    }

    public static function tearDownAfterClass(): void
    {
         self::$container = null;
         self::$dbhZContaineru  = null;
    }
//------------------------------------------------------------------------------------------------
   
    
    
    public function testGet() {                          
          
        /* @var $metaDataProvider MetadataProviderMysql */
        $metaDataProvider = self::$container->get(MetadataProviderMysql::class);
        $hydratorRowObject = new RowObjectHydrator (new NameHydrator(), $metaDataProvider->getTableMetadata('testovaci_table'));         
        $daoTestovaciTable = new TestovaciTableDao( self::$dbhZContaineru, 'testovaci_table', $metaDataProvider, $hydratorRowObject );
        
        /* @var $rowObject TestovaciTableRow */
        $rowObject = $daoTestovaciTable->get(self::HODNOTA_UID_1);            
        //
        $this->assertIsObject($rowObject, "Chyba: ->get(" . self::HODNOTA_UID_1 . ") nevrátil objekt." );                                 
        //typy
        $this->assertIsString( $rowObject->uidPrimarniKlicZnaky, "Chyba: ->uidPrimarniKlicZnaky neobsahuje string." );
        $this->assertIsString( $rowObject->prvekChar,        "Chyba: ->prvekChar neobsahuje string." );
        $this->assertIsString( $rowObject->prvekVarchar,     "Chyba: ->prvekVarchar neobsahuje string." );
        $this->assertIsString( $rowObject->prvekText,        "Chyba: ->prvekText neobsahuje string." );
        
        $this->assertIsObject( $rowObject->prvekDatetime, "Chyba: ->prvekDatetime neobsahuje objekt." );
        
        $this->assertIsObject( $rowObject->prvekDate,     "Chyba: ->prvekDate neobsahuje objekt." );   
        $this->assertIsObject( $rowObject->prvekTimestamp,"Chyba: ->prvekTimestamp neobsahuje objekt." ); 
        $this->assertContainsOnlyInstancesOf( \DateTime::class, [$rowObject->prvekDate],     "Chyba: ->prvekDate neobsahuje objekt typu " . \DateTime::class .  "."  );                         
        $this->assertContainsOnlyInstancesOf( \DateTime::class, [$rowObject->prvekDatetime], "Chyba: ->prvekDatetime neobsahuje objekt typu " . \DateTime::class .  "."  );                
        $this->assertContainsOnlyInstancesOf( \DateTime::class, [$rowObject->prvekTimestamp],"Chyba: ->prvekTimestamp neobsahuje objekt typu " . \DateTime::class .  "."  );                 
                        
        $this->assertIsInt($rowObject->prvekInteger,         "Chyba: ->prvekInteger neobsahuje integer ");
        //$this->assertIsNumeric
        
        //hodnoty  
        /* @var $o \DateTimeInterface */ 
        $o = &$rowObject->prvekDate;
        $oa = $rowObject->prvekDate;
        $o->format("Y-m-d");   
        (new \DateTime())->format ("Y-m-d H:i:s");
                       
        $this->assertEquals( $rowObject->uidPrimarniKlicZnaky, self::HODNOTA_UID_1 , "Chyba: ->uidPrimarniKlicZnaky se nerovna " . self::HODNOTA_UID_1 .  "." );   
        $this->assertEquals( $rowObject->prvekChar,      self::HODNOTA_CHAR ,    "Chyba: ->prvekChar se nerovna " .     self::HODNOTA_CHAR .  "." );   
        $this->assertEquals( $rowObject->prvekVarchar,   self::HODNOTA_VARCHAR , "Chyba: ->prvekVarchar se nerovna " .  self::HODNOTA_VARCHAR .  "." );   
        $this->assertEquals( $rowObject->prvekText,      self::HODNOTA_TEXT ,    "Chyba: ->prvekText se nerovna " .     self::HODNOTA_TEXT .  "." );   
        $this->assertEquals( $rowObject->prvekInteger,   self::HODNOTA_INTEGER , "Chyba: ->prvekInteger se nerovna " .  self::HODNOTA_INTEGER .  "." );           
        $this->assertEquals( $rowObject->prvekDate->format("Y-m-d"), self::$hodnotaDate ,"Chyba: ->prvekDate se nerovna " . self::$hodnotaDate );          
        $this->assertEquals( $rowObject->prvekDatetime->format("Y-m-d H:i:s"), self::$hodnotaDateTime ,"Chyba: ->prvekDatetime se nerovna " . self::$hodnotaDateTime );   
        //------------------------------------------------------------------------------------------------------------------
       
        
        //-----------------------------------  null-ovy radek -----------------
        /* @var $rowObject TestovaciTableRow */
        $rowObject = $daoTestovaciTable->get(self::HODNOTA_UID_2); 
        //
        $this->assertIsObject($rowObject, "Chyba: ->get(" . self::HODNOTA_UID_2 . ") nevrátil objekt." );      
        $this->assertNull($rowObject->prvekChar, "Chyba: ->prvekChar se nerovná null." );
        //------------------------------------------------------------------------------------------------------------------
        
        
         
        //asserts prohlasuje, tvrdi     assertion prohlaseni,tvrzeni
        $this->assertEquals(1,1, "1 se nerovna 1");     
        //$expected, $actual, string $message 
           
    }
    
    
      
//----------------------------------------------------------------  //----------------------------------------------------------------      
//----------------------------------------------------------------  //----------------------------------------------------------------    
//    /**
//     * Testuje Statement s různým fetch mode - jen s SQL SELECT
//     */
//    public function testGet_o() {
//        $container = (new TestDaoContainerConfigurator())->configure(new Container());
//        $dbh = $container->get(Handler::class);
//        /** @var MetadataProviderMysql $metaDataProvider */
//        $metaDataProvider = $container->get(MetadataProviderMysql::class);
//
//        $hydratorRowObject = new RowObjectHydrator (new NameHydrator, $metaDataProvider->getTableMetadata('konfigurace_testu'));
//        $daoKonfiguraceTestu = new KonfiguraceTestuDao($dbh, 'konfigurace_testu', $metaDataProvider, $hydratorRowObject );
//        $uid = '1234567890001';
//        /* @var $rowObjektKonfiguraceTestu KonfiguraceTestuRow */
//        $rowObjektKonfiguraceTestu = $daoKonfiguraceTestu->get($uid);
//        $this->assertNotNull($rowObjektKonfiguraceTestu, "Vrátil NULL.");
//        $this->assertEquals('Model\RowObject\KonfiguraceTestuRow2', get_class($rowObjektKonfiguraceTestu), 'Objekt vytvořený get není očekávaného typu typu Person. Je '.get_class($rowObjektKonfiguraceTestu).'.');
//        $this->assertEquals('1234567890001', $rowObjektKonfiguraceTestu->uidKonfiguraceTestu);
//        $this->assertNotNull($rowObjektKonfiguraceTestu->uidNazevSadyFk);        
//    }
//    
//    public function testGetNull() {  
//        $container = (new TestDaoContainerConfiguratorSV())->configure(new Container());
//        $dbh = $container->get(Handler::class);
//        /** @var MetadataProviderMysql $metaDataProvider */
//        $metaDataProvider = $container->get(MetadataProviderMysql::class);
//
//        $hydratorRowObject = new RowObjectHydrator (new NameHydrator, $metaDataProvider->getTableMetadata('konfigurace_testu'));
//        $daoKonfiguraceTestu = new KonfiguraceTestuDao($dbh, 'konfigurace_testu', $metaDataProvider, $hydratorRowObject );
//        
//        $rowObjektKonfiguraceTestuNeexistujici = $daoKonfiguraceTestu->get('ssd576');
//        $this->assertNull($rowObjektKonfiguraceTestuNeexistujici, "Nevrátil NULL.");   
//    }    
}
