<?php
namespace Test\KeyRowObjectHydratorTest;

use PHPUnit\Framework\TestCase;

use Model\RowObject\RowObjectAbstract;
use Model\RowObject\RowObjectInterface;

use Model\RowObject\Key\KeyAbstract;
use Model\RowObject\Key\KeyInterface;

use \Model\Hydrator\AttributeAccessHydrator;
use Model\Hydrator\NameHydrator\AttributeNameHydratorInterface;
use Model\Filter\ColumnFilterInterface;
use Model\Hydrator\Exception\UndefinedColumnNameException;

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

interface RowObjectInterfaceMock extends RowObjectInterface{
}
class RowObjectMock  extends RowObjectAbstract implements  RowObjectInterfaceMock {
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

//    public function __construct(KeyInterface $key ) {
//        parent::__construct( $key );
//    }

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


//class RowDataMock  extends \ArrayObject  implements RowDataInterface {
//   // use RowDataTrait;
//}


//---------------------------------------------------------------------------------------------------------------------------
class KeyRowObjectHydratorTest_spatny extends TestCase {

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
    public function _testHydrate(): void {
        $poleJmenKey = [ "uidTestovaciTable" ] ;

        /* @var $metaDataProvider MetadataProviderMysql */
        $metaDataProvider = self::$container->get(MetadataProviderMysql::class);
        $keyRowObjectHydrator = new AttributeAccessHydrator ( new AttributeNameHydratorROMock(),
                                                 $metaDataProvider->getTableMetadata('testovaci_table_row'), /* pro zjisteni typu*/
                                                 new KeyColumnFilterMock( $poleJmenKey )
                                               );
        $this->assertIsObject($keyRowObjectHydrator, "***CHYBA***" );

        $rowObjectM =  new RowObjectMock(  new KeyMock( [ "uidTestovaciTable"=>false ] )   );
        $keyRowObjectHydrator->hydrate( $rowObjectM->key , new RowData( [ "uid_testovaci_table" => "KEYklic"  ] ) );

        $this->assertObjectHasAttribute( "uidTestovaciTable", $rowObjectM->key,  "***CHYBA***"  );

        $this->assertEquals( $rowObjectM->key->uidTestovaciTable, "KEYklic" , "***CHYBA***"   );
    }


    public function _testHydrate_UndefinedColumnNameException(): void {
        $poleJmenKey = [ "uidPrimarniKlicZnaky", "klicNeexistujiciSloupec" ] ;

        /* @var $metaDataProvider MetadataProviderMysql */
        $metaDataProvider = self::$container->get(MetadataProviderMysql::class);
        $keyRowObjectHydrator = new AttributeAccessHydrator( new AttributeNameHydratorROMock(),
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


    public function _testExtract(): void {

        $poleJmenKey = [ "uidTestovaciTable" , "klic"] ;

        /* @var $metaDataProvider MetadataProviderMysql */
        $metaDataProvider = self::$container->get( MetadataProviderMysql::class );
        $keyRowObjectHydrator = new AttributeAccessHydrator( new AttributeNameHydratorROMock(),
                                                 $metaDataProvider->getTableMetadata('testovaci_table_row'), /* pro zjisteni typu*/
                                                 new KeyColumnFilterMock( $poleJmenKey ));
        $this->assertIsObject( $keyRowObjectHydrator, "***CHYBA***" );

        //$rowDataM = new RowDataMock ();
        $rowDataM = new RowData ();
        $rowObjectM = new RowObjectMock( new KeyMock( ["uidTestovaciTable"=>false ] ));

        $rowObjectM->key->uidTestovaciTable = "KEYklic";
        $rowObjectM->key->klic = "";

        $keyRowObjectHydrator->extract( $rowObjectM->key, $rowDataM);
        $changed = $rowDataM->fetchChanged();
        $this->assertArrayHasKey( "uid_testovaci_table", $changed, "***CHYBA***"  );
        $this->assertArrayHasKey( "klic", $changed, "***CHYBA***"  );

        $this->assertEquals( $changed["uid_testovaci_table"], "KEYklic" , "***CHYBA***"   );
        $this->assertEquals( $changed["klic"], "" , "***CHYBA***"   );
    }



    public function _testExtract_UndefinedColumnNameException(): void {

        $poleJmenKey = [ "uidPrimarniKlicZnaky", "klicNeexistujici" ] ;

        /* @var $metaDataProvider MetadataProviderMysql */
        $metaDataProvider = self::$container->get( MetadataProviderMysql::class );
        $keyRowObjectHydrator = new AttributeAccessHydrator( new AttributeNameHydratorROMock(),
                                                 $metaDataProvider->getTableMetadata('testovaci_table_row'), /* pro zjisteni typu*/
                                                 new KeyColumnFilterMock( $poleJmenKey ));
        $this->assertIsObject( $keyRowObjectHydrator, "***CHYBA***" );

        //$rowDataM = new RowDataMock ();
        $rowDataM = new RowData ();
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

