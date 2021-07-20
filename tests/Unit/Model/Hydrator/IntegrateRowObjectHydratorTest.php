<?php
namespace Test\IntegrateKeyRowObjectHydratorTest;

use PHPUnit\Framework\TestCase;

use Model\RowObject\RowObjectAbstract;
use Model\RowObject\RowObjectInterface;

use Model\RowObject\Key\KeyAbstract;
use Model\RowObject\Key\KeyInterface;

use Model\Hydrator\AttributeHydrator;
use Model\Hydrator\NameHydrator\AttributeNameHydratorInterface;
use Model\Hydrator\Filter\ColumnFilterInterface;
use Model\Hydrator\Exception\DatetimeConversionFailureException;
use Model\Hydrator\Exception\UndefinedColumnNameException;
use Model\Hydrator\Exception\UncompleteKeyException;



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
 class KeyColumnFilterMock implements ColumnFilterInterface {     
    private $poleJmen;                
    public function __construct( array $poleJmen ) {
        $this->poleJmen = $poleJmen;        
    }    
    public function getIterator() : \Traversable {        
         return new \ArrayIterator( $this->poleJmen );
    }             
 }

interface RowObjectInterfaceMock extends RowObjectInterface {
    
}
class RowObjectMock  extends RowObjectAbstract implements RowObjectInterfaceMock {                  
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
    
    public function __construct( KeyInterfaceMock $key ) {
        parent::__construct( $key );
    }
    
}



interface KeyInterfaceMock extends KeyInterface{    
}
class KeyMock extends KeyAbstract implements KeyInterfaceMock {
    public $uidPrimarniKlicZnaky;
    
     //v Abstract  public $generated?
}


class RowDataMock  extends \ArrayObject  implements RowDataInterface {                  
    use RowDataTrait;
}


//-----------------------------------------------------------------------------------------

/**
 * Description of IntegrateRowObjectTest
 *
 * @author vlse2610
 */
class IntegrateRowObjectHydratorTest extends TestCase {
    
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

//---------------------------------------------------------------------------
    public function testHydrate(): void {   
        $poleJmenDoFiltruHydratoruRO =  [ 
            "prvekChar" , "prvekVarchar", "prvekInteger" ,"prvekText", "prvekBoolean",  
            "prvekDate", "prvekDatetime", "prvekTimestamp"           ] ;   
        $poleJmenKlice = [ "uidPrimarniKlicZnaky" ] ;
        
        /* @var $metaDataProvider MetadataProviderMysql */
        $metaDataProvider = self::$container->get(MetadataProviderMysql::class); 
        $keyRowObjectHydrator = new AttributeHydrator( new AttributeNameHydratorROMock(),  
                                                 $metaDataProvider->getTableMetadata('testovaci_table_row'), /* pro zjisteni typu*/
                                                 new KeyColumnFilterMock( $poleJmenKlice )
                                               );          
        // $this->assertIsObject($keyRowObjectHydrator, "***CHYBA***" );                                        
        $rowObjectHydrator = new AttributeHydrator( new AttributeNameHydratorROMock(),  
                                              $metaDataProvider->getTableMetadata('testovaci_table_row'), /* pro zjisteni typu*/
                                              new ColumnFilterMock( $poleJmenDoFiltruHydratoruRO ) 
                                            );           
        // $this->assertIsObject($rowObjectHydrator, "***CHYBA***" );
                    
         $rowData =  new RowData( [ "prvek_char" => "QWERTZ",
                      "prvek_varchar" => "Qěščřžýáíé",
                      "prvek_integer" => 111,
                      "prvek_text" => "Povídám pohádku",
                      "prvek_boolean" => true,
                      "prvek_date" =>  self::$testDateString,
                      "prvek_datetime" => self::$testDateTimeString,
                      "prvek_timestamp" => self::$testDateTimeString,
                      "uid_primarni_klic_znaky" => "KEYklic"  
             ]  );        
        
        $rowObjectM =  new RowObjectMock(new KeyMock( [ "uidPrimarniKlicZnaky" => false ] ) );               
        $rowObjectHydrator->hydrate( $rowObjectM , $rowData );     
        $keyRowObjectHydrator->hydrate( $rowObjectM->key , $rowData );  
        
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
                       
        //-------------------------------              
        
        $this->assertObjectHasAttribute( "uidPrimarniKlicZnaky", $rowObjectM->key,  "***CHYBA***"  );                        
        $this->assertEquals( $rowObjectM->key->uidPrimarniKlicZnaky, "KEYklic" , "***CHYBA***"   );    
                
    }

    //-------------------------------------------------------------------
    
    
    public function testExtract(): void { 
        $poleJmenDoFiltruHydratoru =  [ 
            "prvekChar" , "prvekVarchar", "prvekInteger" ,"prvekText", "prvekBoolean",  
            "prvekDate", "prvekDatetime", "prvekTimestamp"     ] ;      
        $poleJmenKey = [ "uidPrimarniKlicZnaky" , "klic"] ;
                
        /* @var $metaDataProvider MetadataProviderMysql */
        $metaDataProvider = self::$container->get( MetadataProviderMysql::class ); 
        $rowObjectHydrator = new AttributeHydrator( new AttributeNameHydratorROMock(),  
                                              $metaDataProvider->getTableMetadata('testovaci_table_row'), /* pro zjisteni typu*/
                                              new ColumnFilterMock( $poleJmenDoFiltruHydratoru ) 
                                            );           
        $this->assertIsObject($rowObjectHydrator, "***CHYBA***" );
        $keyRowObjectHydrator = new AttributeHydrator(  new AttributeNameHydratorROMock(),  
                                                     $metaDataProvider->getTableMetadata('testovaci_table_row'), /* pro zjisteni typu*/                                                     
                                                     new KeyColumnFilterMock( $poleJmenKey ));           
        $this->assertIsObject( $keyRowObjectHydrator, "***CHYBA***" );

                 
        $rowDataM = new RowDataMock ();                
        $rowObjectM = new RowObjectMock( new KeyMock( ["uidPrimarniKlicZnaky" => false ] ));    //generated       
        
        $rowObjectM->key->uidPrimarniKlicZnaky = "KEYklic";
        $rowObjectM->key->klic = "";
                
        $rowObjectM->prvekChar = "QWERTZ" ;                                                
        $rowObjectM->prvekVarchar = "Qěščřžýáíé";
        $rowObjectM->prvekInteger = 111;
        $rowObjectM->prvekText = "Povídám pohádku";
        $rowObjectM->prvekBoolean = true;
        $rowObjectM->prvekDate =  self::$hodnotaDate;
        $rowObjectM->prvekDatetime = self::$hodnotaDateTime;
        $rowObjectM->prvekTimestamp = self::$hodnotaDateTime;
                        
        $rowObjectHydrator->extract( $rowObjectM, $rowDataM); 
        $keyRowObjectHydrator->extract( $rowObjectM->key, $rowDataM);   
        
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
        
        //------------------------------------------------
        
        $this->assertArrayHasKey( "uid_primarni_klic_znaky", $rowDataM->getChanged(), "***CHYBA***"  );       
        $this->assertArrayHasKey( "klic", $rowDataM->getChanged(), "***CHYBA***"  );       
        
        $this->assertEquals( $rowDataM->getChanged()["uid_primarni_klic_znaky"], "KEYklic" , "***CHYBA***"   );
        $this->assertEquals( $rowDataM->getChanged()["klic"], "" , "***CHYBA***"   );
   
    }

    
}
