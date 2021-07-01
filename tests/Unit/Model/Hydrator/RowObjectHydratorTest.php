<?php
namespace Test\RowHydratorTest;

use PHPUnit\Framework\TestCase;

use Model\RowObject\AttributeInterface;
use Model\RowObject\RowObjectAbstract;
use Model\Hydrator\AttributeHydrator;
use Model\Hydrator\NameHydrator\AttributeNameHydratorInterface;
use Model\Hydrator\Filter\ColumnFilterInterface;
use Model\Hydrator\Exception\DatetimeConversionFailureException;
use Model\Hydrator\Exception\UndefinedColumnNameException;
//use Model\RowObject\Hydrator\Exception\UnknownPropertyNameException;

use Model\RowObject\Key\KeyAbstract;
//use Model\RowObject\RowInterface;

use Model\RowData\RowData;
use Model\RowData\RowDataInterface;
use Model\RowData\RowDataTrait;

use Test\Configuration\DaoContainerConfigurator;
use Pes\Container\Container;
// database
use Pes\Database\Handler\Handler;
use Pes\Database\Metadata\MetadataProviderMysql;



class AttributeNameHydratorROMock implements AttributeNameHydratorInterface {    
    public function hydrate(/*$underscoredName*/ $camelCaseName ){
        //return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $underscoredName))));
        return strtolower(preg_replace('/(?<!^)([A-Z])/', '_$1', $camelCaseName));
    }

    public function extract( /*$underscoredName*/ $camelCaseName ) {                
       //$s2 = strtolower(preg_replace('/(?<!^)([A-Z])/', '_$1', $camelCaseName));
       return strtolower(preg_replace('/(?<!^)([A-Z])/', '_$1', $camelCaseName));  
       //return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $underscoredName))));
    }
}

 class ColumnFilterMock implements ColumnFilterInterface {     
    private $poleJmen;                
    public function __construct( array $poleJmen ) {
        $this->poleJmen = $poleJmen;        
    }    
    public function getIterator() : \Traversable {        
         return new \ArrayIterator( $this->poleJmen );
    }             
 } 
 

class RowObjectMock  extends RowObjectAbstract implements AttributeInterface {                  
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
    
    //v Abstract  public $key
    public function __construct( AttributeInterface $key ) {
        parent::__construct( $key );
    }
}

class KeyMock extends KeyAbstract implements AttributeInterface {
    public $uidPrimarniKlicZnaky;
    
     //v Abstract  public? $generated
}


class RowDataMock  extends \ArrayObject  implements RowDataInterface {                  
    use RowDataTrait;
}

 
//---------------------------------------------------------------------------------------------------------------------------
class RowHydratorTest extends TestCase {
    
    const DB_NAME = 'tester_3_test';
    const DB_HOST = 'localhost';
    const USER = 'root';
    const PASS = 'spravce';

    protected static $testDateString;
    protected static $testDateTimeString;
    protected static $hodnotaDate;
    protected static $hodnotaDateTime;
    
    protected static  $dbhZContaineru;
    protected static  $container;
       
    
    public function setUp(): void {               
    }       
    
    public static function setUpBeforeClass(): void    {
        self::$container = (new DaoContainerConfigurator())->configure(new Container());
        self::$dbhZContaineru  = self::$container->get(Handler::class); // $dbh = $container->get(Handler::class); 
        
            // 1 -  nastaveni "konstant"
        self::$testDateString = "2010-09-08";
        self::$hodnotaDate = \DateTime::createFromFormat("Y-m-d", self::$testDateString = "2010-09-08" )->setTime(0,0,0,0); 
        self::$testDateTimeString = "2005-06-07 22:23:24";
        self::$hodnotaDateTime = \DateTime::createFromFormat("Y-m-d H:i:s", self::$testDateTimeString );               
    }
    
    public static function tearDownAfterClass(): void    {
        self::$container = null;
        self::$dbhZContaineru  = null;
    }
            
    //---------------------------------------------------------------------------------------------------------
    
    public function testHydrate(): void { 
        $poleJmenAttributes =  [ 
//            "prvek_char" , "prvek_varchar", "prvek_integer" ,"prvek_text", "prvek_boolean",  
//            "prvek_date", "prvek_datetime", "prvek_timestamp"   "
            "prvekChar" , "prvekVarchar", "prvekInteger" ,"prvekText", "prvekBoolean",  
            "prvekDate", "prvekDatetime", "prvekTimestamp"          ] ;     
        
        
        /* @var $metaDataProvider MetadataProviderMysql */
        $metaDataProvider = self::$container->get(MetadataProviderMysql::class); 
        $rowHydrator = new AttributeHydrator( new AttributeNameHydratorROMock(),  
                                              $metaDataProvider->getTableMetadata('testovaci_table_row'), /* pro zjisteni typu*/
                                              new ColumnFilterMock( $poleJmenAttributes )
                                            );          
        $this->assertIsObject($rowHydrator, "***CHYBA***" );
                         
        $rowObjectM =  new RowObjectMock(new KeyMock( [], []));      
        $rowHydrator->hydrate( $rowObjectM , new RowData( [ "prvek_char" => "QWERTZ",
                                                                  "prvek_varchar" => "Qěščřžýáíé",
                                                                  "prvek_integer" => 111,
                                                                  "prvek_text" => "Povídám pohádku",
                                                                  "prvek_boolean" => true,
                                                                  "prvek_date" =>  self::$testDateString,
                                                                  "prvek_datetime" => self::$testDateTimeString,
                                                                  "prvek_timestamp" => self::$testDateTimeString,
                                                          ] ) );       
        $this->assertObjectHasAttribute( "prvekChar",     $rowObjectM, "***CHYBA***"  );
        $this->assertObjectHasAttribute( "prvekVarchar",  $rowObjectM, "***CHYBA***"  );
        $this->assertObjectHasAttribute( "prvekInteger",  $rowObjectM, "***CHYBA***"  );
        $this->assertObjectHasAttribute( "prvekText",     $rowObjectM, "***CHYBA***"  );
        $this->assertObjectHasAttribute( "prvekBoolean",  $rowObjectM, "***CHYBA***"  );
        $this->assertObjectHasAttribute( "prvekDate",     $rowObjectM, "***CHYBA***"  );
        $this->assertObjectHasAttribute( "prvekDatetime", $rowObjectM, "***CHYBA***"  );
        $this->assertObjectHasAttribute( "prvekTimestamp",$rowObjectM, "***CHYBA***"  );
      
        
        $this->assertEquals( $rowObjectM->prvekChar,        "QWERTZ", "***CHYBA***"   );
        $this->assertEquals( $rowObjectM->prvekVarchar,     "Qěščřžýáíé", "***CHYBA***"   );
        $this->assertEquals( $rowObjectM->prvekInteger,     111, "***CHYBA***"   );
        $this->assertEquals( $rowObjectM->prvekText,        "Povídám pohádku", "***CHYBA***"   );
        $this->assertEquals( $rowObjectM->prvekBoolean,     true, "***CHYBA***"   );
        $this->assertEquals( $rowObjectM->prvekDate,        self::$hodnotaDate, "***CHYBA***"   );
        $this->assertEquals( $rowObjectM->prvekDatetime,    self::$hodnotaDateTime, "***CHYBA***"   );
        $this->assertEquals( $rowObjectM->prvekTimestamp,   self::$hodnotaDateTime, "***CHYBA***"   ); 
    }
    //----------------------------------------------------------------
    
    public function testHydrate_UndefinedColumnNameException(): void { 
        $poleJmenAttributes =  [ 
//            "prvek_char" , "prvek_varchar", "prvek_integer" ,"prvek_text", "prvek_boolean",  
//            "prvek_date", "prvek_datetime", "prvek_timestamp"   "
            "prvekChar" , "prvekVarchar", "prvekInteger" ,"prvekText", "prvekBoolean",  
            "prvekDate", "prvekDatetime", "prvekTimestamp" , "prvekSloupecNeexistujici"  ] ;             
        
        /* @var $metaDataProvider MetadataProviderMysql */
        $metaDataProvider = self::$container->get(MetadataProviderMysql::class); 
        $rowHydrator = new AttributeHydrator( new AttributeNameHydratorROMock(),  
                                        $metaDataProvider->getTableMetadata('testovaci_table_row'), /* pro zjisteni typu*/
                                        new ColumnFilterMock( $poleJmenAttributes )  //--s neex.sloupcem
                                      );          
        $this->assertIsObject($rowHydrator, "***CHYBA***" );
        
        $rowObjectM =  new RowObjectMock(new KeyMock( [],[] ));      
        $this->expectException( UndefinedColumnNameException::class );
        $rowHydrator->hydrate( $rowObjectM , new RowData( [ "prvek_char" => "QWERTZ",
                                                                  "prvek_varchar" => "Qěščřžýáíé",
                                                                  "prvek_integer" => 111,
                                                                  "prvek_text" => "Povídám pohádku",
                                                                  "prvek_boolean" => true,
                                                                  "prvek_date" =>  self::$testDateString,
                                                                  "prvek_datetime" => self::$testDateTimeString,
                                                                  "prvek_timestamp" => self::$testDateTimeString                                                               
                                                          ] ) );       
    }
    
    
    
    public function testHydrate_DatetimeConversionFailureException_Date(): void { 
        $poleJmenAttributes =  [ 
//            "prvek_char" , "prvek_varchar", "prvek_integer" ,"prvek_text", "prvek_boolean",  
//            "prvek_date", "prvek_datetime", "prvek_timestamp"   "
            "prvekChar" , "prvekVarchar", "prvekInteger" ,"prvekText", "prvekBoolean",  
            "prvekDate", "prvekDatetime", "prvekTimestamp"          ] ;             
        
        /* @var $metaDataProvider MetadataProviderMysql */
        $metaDataProvider = self::$container->get(MetadataProviderMysql::class); 
        $rowtHydrator = new AttributeHydrator( new AttributeNameHydratorROMock(),  
                                              $metaDataProvider->getTableMetadata('testovaci_table_row'), /* pro zjisteni typu*/
                                              new ColumnFilterMock( $poleJmenAttributes )
                                            );          
        $this->assertIsObject($rowtHydrator, "***CHYBA***" );
        
        $rowObjectM =  new RowObjectMock(new KeyMock([],[]));           
        $this->expectException( DatetimeConversionFailureException::class );        
        $rowtHydrator->hydrate( $rowObjectM , new RowData( [ "prvek_char" => "QWERTZ",
                                                                  "prvek_varchar" => "Qěščřžýáíé",
                                                                  "prvek_integer" => 111,
                                                                  "prvek_text" => "Povídám pohádku",
                                                                  "prvek_boolean" => true,
                                                                  "prvek_date" =>  "2010-lpp"                                                                                                                                                                                              
                                                               ] ) );              
    }
    
    
    public function testHydrate_DatetimeConversionFailureException_Datetime(): void { 
        $poleJmenAttributes =  [ 
//            "prvek_char" , "prvek_varchar", "prvek_integer" ,"prvek_text", "prvek_boolean",  
//            "prvek_date", "prvek_datetime", "prvek_timestamp"   "
            "prvekChar" , "prvekVarchar", "prvekInteger" ,"prvekText", "prvekBoolean",  
            "prvekDate", "prvekDatetime", "prvekTimestamp"          ] ;     
        
        /* @var $metaDataProvider MetadataProviderMysql */
        $metaDataProvider = self::$container->get(MetadataProviderMysql::class); 
        $rowHydrator = new AttributeHydrator( new AttributeNameHydratorROMock(),  
                                              $metaDataProvider->getTableMetadata('testovaci_table_row'), /* pro zjisteni typu*/
                                              new ColumnFilterMock( $poleJmenAttributes )
                                            );          
        $this->assertIsObject($rowHydrator, "***CHYBA***" );
        
        $rowObjectM =  new RowObjectMock(new KeyMock( [],[] ));          
        $this->expectException( DatetimeConversionFailureException::class );        
        $rowHydrator->hydrate( $rowObjectM , new RowData( [ "prvek_char" => "QWERTZ",
                                                                  "prvek_varchar" => "Qěščřžýáíé",
                                                                  "prvek_integer" => 111,
                                                                  "prvek_text" => "Povídám pohádku",
                                                                  "prvek_boolean" => true,                                                                  
                                        //                        "prvek_datetime" => "2005-23-22 22:23:24"  
                                        // !!! s timto datumem nema  \DateTime::createFromFormat problém    
                                        // !!! akorát vyjde jině datum
                                                                  "prvek_datetime" => "2005-opp 22:23:24"  
                                                               ] ) );              
//        $this->assertEquals( $rowObjectM->prvekDatetime,  \DateTime::createFromFormat("Y-m-d H:i:s", "2005-23-22 22:23:24")  , "***CHYBA***"   );        
//        print_r ($rowObjectM->prvekDatetime->format("Y-m-d H:i:s"));
//        echo ($rowObjectM->prvekDatetime->format("Y-m-d H:i:s"));
        
    }
        
    //----------------------------------------------------------------------------------------------------------        
    
    public function testExtract(): void { 
        $poleJmenDoFiltruHydratoru =  [ 
//            "prvek_char" , "prvek_varchar", "prvek_integer" ,"prvek_text", "prvek_boolean",  
//            "prvek_date", "prvek_datetime", "prvek_timestamp" 
            "prvekChar" , "prvekVarchar", "prvekInteger" ,"prvekText", "prvekBoolean",  
            "prvekDate", "prvekDatetime", "prvekTimestamp"     ] ;          
                
        /* @var $metaDataProvider MetadataProviderMysql */
        $metaDataProvider = self::$container->get( MetadataProviderMysql::class ); 
        $rowHydrator = new AttributeHydrator( new AttributeNameHydratorROMock(),  
                                        $metaDataProvider->getTableMetadata('testovaci_table_row'), /* pro zjisteni typu*/
                                        new ColumnFilterMock( $poleJmenDoFiltruHydratoru ) 
                                      );           
        $this->assertIsObject($rowHydrator, "***CHYBA***" );
                 
        $rowDataM = new RowDataMock ();                
        $rowObjectM = new RowObjectMock( new KeyMock( [],[] ));           
        
        $rowObjectM->prvekChar = "QWERTZ" ;                                                
        $rowObjectM->prvekVarchar = "Qěščřžýáíé";
        $rowObjectM->prvekInteger = 111;
        $rowObjectM->prvekText = "Povídám pohádku";
        $rowObjectM->prvekBoolean = true;
        $rowObjectM->prvekDate =  self::$hodnotaDate;
        $rowObjectM->prvekDatetime = self::$hodnotaDateTime;
        $rowObjectM->prvekTimestamp = self::$hodnotaDateTime;
                        
        $rowHydrator->extract( $rowObjectM, $rowDataM); 
        
        $this->assertArrayHasKey( "prvek_char",      $rowDataM->getChanged(), "***CHYBA***"  );
        $this->assertArrayHasKey( "prvek_varchar",   $rowDataM->getChanged(), "***CHYBA***"  );
        $this->assertArrayHasKey( "prvek_integer",   $rowDataM->getChanged(), "***CHYBA***"  );
        $this->assertArrayHasKey( "prvek_text",      $rowDataM->getChanged(), "***CHYBA***"  );
        $this->assertArrayHasKey( "prvek_boolean",   $rowDataM->getChanged(), "***CHYBA***"  );
        $this->assertArrayHasKey( "prvek_date",      $rowDataM->getChanged(), "***CHYBA***"  );
        $this->assertArrayHasKey( "prvek_datetime",  $rowDataM->getChanged(), "***CHYBA***"  );
        $this->assertArrayHasKey( "prvek_timestamp", $rowDataM->getChanged(), "***CHYBA***"  );    
       
        $this->assertEquals( $rowDataM->getChanged()["prvek_char"],        "QWERTZ", "***CHYBA***"   );
        $this->assertEquals( $rowDataM->getChanged()["prvek_varchar"],     "Qěščřžýáíé", "***CHYBA***"   );
        $this->assertEquals( $rowDataM->getChanged()["prvek_integer"],     111, "***CHYBA***"   );
        $this->assertEquals( $rowDataM->getChanged()["prvek_text"],        "Povídám pohádku", "***CHYBA***"   );
        $this->assertEquals( $rowDataM->getChanged()["prvek_boolean"],     true, "***CHYBA***"   );
        $this->assertEquals( $rowDataM->getChanged()["prvek_date"],        self::$testDateString, "***CHYBA***"   );
        $this->assertEquals( $rowDataM->getChanged()["prvek_datetime"],    self::$testDateTimeString, "***CHYBA***"   );
        $this->assertEquals( $rowDataM->getChanged()["prvek_timestamp"],   self::$testDateTimeString, "***CHYBA***"   );   
    }
    
    public function testExtract_UndefinedColumnNameException(): void {         
        $poleJmenDoFiltruHydratoru =  [ 
//            "prvek_char" , "prvek_varchar", "prvek_integer" ,"prvek_text", "prvek_boolean",  
//            "prvek_date", "prvek_datetime", "prvek_timestamp" 
            "prvekChar" , "prvekVarchar", "prvekInteger" ,"prvekText", "prvekBoolean",  
            "prvekDate", "prvekDatetime", "prvekTimestamp"  , "prvekSloupecNeexistujici"          ] ;          
                
        /* @var $metaDataProvider MetadataProviderMysql */
        $metaDataProvider = self::$container->get( MetadataProviderMysql::class ); 
        $rowHydrator = new AttributeHydrator( new AttributeNameHydratorROMock(),  
                                        $metaDataProvider->getTableMetadata('testovaci_table_row'), /* pro zjisteni typu*/
                                        new ColumnFilterMock( $poleJmenDoFiltruHydratoru ) 
                                      );           
        $this->assertIsObject($rowHydrator, "***CHYBA***" );
        
        $rowDataM = new RowDataMock ();                
        $rowObjectM = new RowObjectMock( new KeyMock( [],[] ));           
        
        $rowObjectM->prvekChar = "QWERTZ" ;                                                
        $rowObjectM->prvekVarchar = "Qěščřžýáíé";
        $rowObjectM->prvekInteger = 111;
        $rowObjectM->prvekText = "Povídám pohádku";
        $rowObjectM->prvekBoolean = true;
        $rowObjectM->prvekDate =  self::$hodnotaDate;
        $rowObjectM->prvekDatetime = self::$hodnotaDateTime;
        $rowObjectM->prvekTimestamp = self::$hodnotaDateTime; 
        
        $rowObjectM->prvekSloupecNeexistujici = "sloupec neni v db"; 
        
        $this->expectException( UndefinedColumnNameException::class );
        $rowHydrator->extract( $rowObjectM, $rowDataM); 
    }
       
    
    public function testExtract_DatetimeConversionFailureException_Date(): void { 
        $poleJmenDoFiltruHydratoru =  [ 
//            "prvek_char" , "prvek_varchar", "prvek_integer" ,"prvek_text", "prvek_boolean",  
//            "prvek_date", "prvek_datetime", "prvek_timestamp" 
            "prvekChar" , "prvekVarchar", "prvekInteger" ,"prvekText", "prvekBoolean",  
            "prvekDate", "prvekDatetime", "prvekTimestamp"           ] ;          
                
        /* @var $metaDataProvider MetadataProviderMysql */
        $metaDataProvider = self::$container->get( MetadataProviderMysql::class ); 
        $rowHydrator = new AttributeHydrator( new AttributeNameHydratorROMock(),  
                                        $metaDataProvider->getTableMetadata('testovaci_table_row'), /* pro zjisteni typu*/
                                        new ColumnFilterMock( $poleJmenDoFiltruHydratoru ) 
                                      );           
        $this->assertIsObject($rowHydrator, "***CHYBA***" );
        
        $rowDataM = new RowDataMock ();                
        $rowObjectM = new RowObjectMock( new KeyMock( [],[] ));           
        
        $rowObjectM->prvekChar = "QWERTZ" ;                                                
        $rowObjectM->prvekVarchar = "Qěščřžýáíé";
        $rowObjectM->prvekInteger = 111;
        $rowObjectM->prvekText = "Povídám pohádku";
        $rowObjectM->prvekBoolean = true;
        $rowObjectM->prvekDate =  self::$hodnotaDate;
        $rowObjectM->prvekDatetime = "hodnotaXstringDate";
        $rowObjectM->prvekTimestamp = self::$hodnotaDateTime;
        
        $this->expectException( DatetimeConversionFailureException::class );        
        $rowHydrator->extract( $rowObjectM, $rowDataM);                        
    }
    
    public function testExtract_DatetimeConversionFailureException_Datetime(): void { 
        $poleJmenDoFiltruHydratoru =  [ 
//            "prvek_char" , "prvek_varchar", "prvek_integer" ,"prvek_text", "prvek_boolean",  
//            "prvek_date", "prvek_datetime", "prvek_timestamp" 
            "prvekChar" , "prvekVarchar", "prvekInteger" ,"prvekText", "prvekBoolean",  
            "prvekDate", "prvekDatetime", "prvekTimestamp"           ] ;          
        
        
        /* @var $metaDataProvider MetadataProviderMysql */
        $metaDataProvider = self::$container->get( MetadataProviderMysql::class ); 
        $rowHydrator = new AttributeHydrator(  new AttributeNameHydratorROMock(),  
                                                     $metaDataProvider->getTableMetadata('testovaci_table_row'), /* pro zjisteni typu*/
                                                     new ColumnFilterMock( $poleJmenDoFiltruHydratoru ) 
                                                   );           
        $this->assertIsObject($rowHydrator, "***CHYBA***" );
        
        $rowDataM = new RowDataMock ();                
        $rowObjectM = new RowObjectMock( new KeyMock( [],[] ));           
        
        $rowObjectM->prvekChar = "QWERTZ" ;                                                
        $rowObjectM->prvekVarchar = "Qěščřžýáíé";
        $rowObjectM->prvekInteger = 111;
        $rowObjectM->prvekText = "Povídám pohádku";
        $rowObjectM->prvekBoolean = true;
        $rowObjectM->prvekDate =  self::$hodnotaDate;
        $rowObjectM->prvekDatetime = "hodnotaXstringDatetime";  
        $rowObjectM->prvekTimestamp = self::$hodnotaDateTime;
                        
        $this->expectException( DatetimeConversionFailureException::class );
        $rowHydrator->extract( $rowObjectM, $rowDataM);    
    }
    
}

