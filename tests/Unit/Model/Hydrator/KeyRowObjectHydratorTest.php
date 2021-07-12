<?php
namespace Test\KeyRowObjectHydratorTest;

use PHPUnit\Framework\TestCase;

use Model\RowObject\AttributeInterface;
use Model\RowObject\RowObjectAbstract;
use Model\Hydrator\AttributeHydrator;
use Model\Hydrator\NameHydrator\AttributeNameHydratorInterface;
use Model\Hydrator\Filter\ColumnFilterInterface;
use Model\Hydrator\Exception\DatetimeConversionFailureException;
use Model\Hydrator\Exception\UndefinedColumnNameException;
use Model\Hydrator\Exception\UncompleteKeyException;

use Model\RowObject\Key\KeyAbstract;
//use Model\RowObject\Key\KeyInterface;

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

 
 class KeyColumnFilterMock implements ColumnFilterInterface {     
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
    
     //v Abstract  public $generated?
}


class RowDataMock  extends \ArrayObject  implements RowDataInterface {                  
    use RowDataTrait;
}

 
//---------------------------------------------------------------------------------------------------------------------------
class KeyRowObjectHydratorTest extends TestCase {
    
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
        $poleJmenKey = [ "uidPrimarniKlicZnaky" ] ;
        
        /* @var $metaDataProvider MetadataProviderMysql */
        $metaDataProvider = self::$container->get(MetadataProviderMysql::class); 
        $keyRowObjectHydrator = new AttributeHydrator( new AttributeNameHydratorROMock(),  
                                                 $metaDataProvider->getTableMetadata('testovaci_table_row'), /* pro zjisteni typu*/
                                                 new KeyColumnFilterMock( $poleJmenKey )
                                               );          
        $this->assertIsObject($keyRowObjectHydrator, "***CHYBA***" );
                         
        $rowObjectM =  new RowObjectMock( new KeyMock( [ "uidPrimarniKlicZnaky"=>false ] ) );      
        $keyRowObjectHydrator->hydrate( $rowObjectM->key , new RowData( [                                                           
                                        "uid_primarni_klic_znaky" => "KEYklic"  ] ) );               
        
        $this->assertObjectHasAttribute( "uidPrimarniKlicZnaky", $rowObjectM->key,  "***CHYBA***"  );                
        
        $this->assertEquals( $rowObjectM->key->uidPrimarniKlicZnaky, "KEYklic" , "***CHYBA***"   );        
    }
    
    
    public function testHydrate_UndefinedColumnNameException(): void { 
        $poleJmenKey = [ "uidPrimarniKlicZnaky", "klicNeexistujiciSloupec" ] ;
        
        /* @var $metaDataProvider MetadataProviderMysql */
        $metaDataProvider = self::$container->get(MetadataProviderMysql::class); 
        $keyRowObjectHydrator = new AttributeHydrator( new AttributeNameHydratorROMock(),  
                                                 $metaDataProvider->getTableMetadata('testovaci_table_row'), /* pro zjisteni typu*/
                                                 new KeyColumnFilterMock( $poleJmenKey )
                                               );          
        $this->assertIsObject($keyRowObjectHydrator, "***CHYBA***" );
                         
        $rowObjectM =  new RowObjectMock(new KeyMock( ["uidPrimarniKlicZnaky"=>false] ) );   
        
        $this->expectException( UndefinedColumnNameException::class );
        $keyRowObjectHydrator->hydrate( $rowObjectM->key , new RowData( [                                                           
                                                                "uid_primarni_klic_znaky" => "KEYklic"  ] ) );                               
    }
    
    
//    public function testHydrate_UncompleteKeyException(): void { 
//        $poleJmenKey = [ "uidPrimarniKlicZnaky" ] ;
//        
//        /* @var $metaDataProvider MetadataProviderMysql */
//        $metaDataProvider = self::$container->get(MetadataProviderMysql::class); 
//        $keyRowObjectHydrator = new  RowHydrator( new AttributeNameHydratorROMock(),  
//                                                  $metaDataProvider->getTableMetadata('testovaci_table_row'), /* pro zjisteni typu*/
//                                                  new KeyColumnFilterMock( $poleJmenKey )
//                                                );          
//        $this->assertIsObject($keyRowObjectHydrator, "***CHYBA***" );
//                         
//        $rowObjectM =  new RowObjectMock(new KeyMock(  ["uidPrimarniKlicZnaky"=>false ] ));   
//        
//        $this->expectException( UncompleteKeyException::class );
//        $keyRowObjectHydrator->hydrate( $rowObjectM->key , new RowData( [                                                           
//                                                                /*"uid_primarni_klic_znaky" => "KEYklic" */ ] ) );    
//        
//    } 
    
    
//    public function testHydrate_DatetimeConversionFailureException_Date(): void {         
//    }
//    public function testHydrate_DatetimeConversionFailureException_Datetime(): void {         
//    }
    
    
    //----------------------------------------------------------------------------------------------
    
    
    public function testExtract(): void { 
        
        $poleJmenKey = [ "uidPrimarniKlicZnaky" , "klic"] ;
        
        /* @var $metaDataProvider MetadataProviderMysql */
        $metaDataProvider = self::$container->get( MetadataProviderMysql::class ); 
        $keyRowObjectHydrator = new AttributeHydrator( new AttributeNameHydratorROMock(),  
                                                 $metaDataProvider->getTableMetadata('testovaci_table_row'), /* pro zjisteni typu*/                                                     
                                                 new KeyColumnFilterMock( $poleJmenKey ));           
        $this->assertIsObject( $keyRowObjectHydrator, "***CHYBA***" );
                 
        $rowDataM = new RowDataMock ();                
        $rowObjectM = new RowObjectMock( new KeyMock( ["uidPrimarniKlicZnaky"=>false ] ));           
        
        $rowObjectM->key->uidPrimarniKlicZnaky = "KEYklic";
        $rowObjectM->key->klic = "";
                
        $keyRowObjectHydrator->extract( $rowObjectM->key, $rowDataM);         
      
        $this->assertArrayHasKey( "uid_primarni_klic_znaky", $rowDataM->getChanged(), "***CHYBA***"  );       
        $this->assertArrayHasKey( "klic", $rowDataM->getChanged(), "***CHYBA***"  );       
        
        $this->assertEquals( $rowDataM->getChanged()["uid_primarni_klic_znaky"], "KEYklic" , "***CHYBA***"   );
        $this->assertEquals( $rowDataM->getChanged()["klic"], "" , "***CHYBA***"   );
    }
    
    
    
    public function testExtract_UndefinedColumnNameException(): void { 
        
        $poleJmenKey = [ "uidPrimarniKlicZnaky", "klicNeexistujici" ] ;
        
        /* @var $metaDataProvider MetadataProviderMysql */
        $metaDataProvider = self::$container->get( MetadataProviderMysql::class ); 
        $keyRowObjectHydrator = new AttributeHydrator( new AttributeNameHydratorROMock(),  
                                                 $metaDataProvider->getTableMetadata('testovaci_table_row'), /* pro zjisteni typu*/                                                     
                                                 new KeyColumnFilterMock( $poleJmenKey ));           
        $this->assertIsObject( $keyRowObjectHydrator, "***CHYBA***" );
                 
        $rowDataM = new RowDataMock ();                
        $rowObjectM = new RowObjectMock( new KeyMock( ["uidPrimarniKlicZnaky"=>false ] ));           
        
        $rowObjectM->key->uidPrimarniKlicZnaky = "KEYklic";
        $rowObjectM->key->klicNeexistujici = "neex";
         
        $this->expectException( UndefinedColumnNameException::class );      
        $keyRowObjectHydrator->extract( $rowObjectM->key, $rowDataM); 
              
    }
    
    
//    public function testExtract_UncompleteKeyException(): void { 
//           
//        $poleJmenKey = [ "uidPrimarniKlicZnaky" ] ;
//        
//        /* @var $metaDataProvider MetadataProviderMysql */
//        $metaDataProvider = self::$container->get( MetadataProviderMysql::class ); 
//        $keyRowObjectHydrator = new RowHydrator(  new AttributeNameHydratorROMock(),  
//                                                     $metaDataProvider->getTableMetadata('testovaci_table_row'), /* pro zjisteni typu*/                                                     
//                                                     new KeyColumnFilterMock( $poleJmenKey ));           
//        $this->assertIsObject( $keyRowObjectHydrator, "***CHYBA***" );
//                 
//        $rowDataM = new RowDataMock ();                
//        $rowObjectM = new RowObjectMock( new KeyMock( ["uidPrimarniKlicZnaky"=>false ] ));           
//        
//        //$rowObjectM->key->uidPrimarniKlicZnaky = "";     
//        // tj. nenastaveno, neexistuje
//         
//        $this->expectException( UncompleteKeyException::class );      
//        $keyRowObjectHydrator->extract( $rowObjectM->key, $rowDataM);                     
//        
//    }    
    
//    public function testExtract_DatetimeConversionFailureException_Date(): void { 
//    }
//    public function testExtract_DatetimeConversionFailureException_Datetime(): void { 
//    }
    
    
}

