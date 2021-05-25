<?php
// kontejner
use Test\TestDaoContainerConfiguratorSV;
use Pes\Container\Container;
// database
use Pes\Database\Handler\Handler;
use Pes\Database\Metadata\MetadataProviderMysql;

use Model\Dao\KonfiguraceTestuDao;
use Model\RowObject\Hydrator\RowObjectHydrator;
use Model\RowObject\Hydrator\NameHydrator;

//use Model\RowObject\RowObjectInterface;
use Model\RowObject\KonfiguraceTestuRow;


//
include '../vendor/Pes/Pes/src/Bootstrap/Bootstrap.php';

//*************************************************************
// VYPNUTÍ KVŮLI EACH() V QUICKFORM2
error_reporting(E_ALL & ~E_DEPRECATED);

    const DB_NAME = 'tester_3';
    const DB_HOST = 'localhost';

    const USER = 'root';
    const PASS = 'spravce';
    
function setUp(): void {
        //fixture:
        //vymaaže tabulku, zapíše tři řádky v UTF8
        $dsn = 'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME ;
        $dbh = new PDO($dsn, self::USER, self::PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
        $dbh->exec('DELETE FROM person');
        $dbh->exec('INSERT INTO person (number, name, surname) VALUES (1, "Adam","Adamov")');
        $dbh->exec('INSERT INTO person (number, name, surname) VALUES (2, "Božena","Boženová")');
        $dbh->exec('INSERT INTO person (number, name, surname) VALUES (3, "Cyril","'.self::TESTOVACI_STRING.'")');

//        $this->connectionInfoUtf8 = new ConnectionInfo(self::NICK, DbTypeEnum::MySQL, self::DB_HOST, self::USER, self::PASS, self::DB_NAME, self::CHARSET_UTF8, self::COLLATION_UTF8, self::DB_PORT);
//        $this->dsnProvider = new DsnProviderMysql();
//        $this->optionsProvider = new OptionsProviderMysql();
//        $this->logger = new NullLogger();
//        $this->attributesProviderDefault = new AttributesProviderDefault($this->logger);

}

echo "<!doctype html>";
echo "<html lang=cs>";

$container = (new TestDaoContainerConfiguratorSV())->configure(new Container());
$dbh = $container->get(Handler::class);
/** @var MetadataProviderMysql $metaDataProvider */
$metaDataProvider = $container->get(MetadataProviderMysql::class);

//----------------------------------------
//----------------------------------------
echo "<h3>Test DAO KonfiguraceTestu</h3>";
$hydratorRowObject = new RowObjectHydrator (new NameHydrator, $metaDataProvider->getTableMetadata('konfigurace_testu'));
$daoKonfiguraceTestu = new KonfiguraceTestuDao($dbh, 'konfigurace_testu', $metaDataProvider, $hydratorRowObject );

/*-------------------------------*/
echo "<p><b>-- get --</b></p>";            // datovy objekt --get
$uid = '1234567890001';
/* @var $rowObjektKonfiguraceTestu KonfiguraceTestuRow */
$rowObjektKonfiguraceTestu = $daoKonfiguraceTestu->get($uid);
var_dump($rowObjektKonfiguraceTestu);

/*------------------------------*/
echo "<p><b>-- find --</b></p>";            // pole datovych objektu --find
$rowObjektKonfiguraceTestuAll = $daoKonfiguraceTestu->find(
                                'uid_konfigurace_testu like :uido order by :ord ' ,
                                [ 'uido' => '12%', 'ord' =>'uid_konfigurace_testu' ] );
var_dump($rowObjektKonfiguraceTestuAll);
//posledni
//var_dump($rowObjektKonfiguraceTestuAll[array_key_last($rowObjektKonfiguraceTestuAll)]);


/*-------------------------------- */
echo "<p><b>-- save --</b></p>";
echo "<br/><b>zapisuji objekt s:  uidKonfiguraceTestu = NULL, persisted = false </b> - bude insert";  //insert
/* @var $zapisovaciO KonfiguraceTestuRow */
$zapisovaciO = clone ($rowObjektKonfiguraceTestuAll[array_key_last($rowObjektKonfiguraceTestuAll) ] ); 
//clone zkopiruju si objekt - posledni z findu
$zapisovaciO->uidKonfiguraceTestu =   NULL;  //
$zapisovaciO->setPersisted(false);
var_dump($zapisovaciO);
$daoKonfiguraceTestu->save( $zapisovaciO );

echo "<b>objekt po zápisu:</b>";
echo "<br/><b>-persisted musí být true : </b>" ; 
echo ( ($zapisovaciO->isPersisted())? "OK" : "chyba");
echo "<br/><b>-uidKonfiguraceTestu musí mít neNULL hodnotu:</b> " ;
echo (isset($zapisovaciO->uidKonfiguraceTestu)? "OK": "chyba"); 
var_dump($zapisovaciO);



// dalsi moznosti
//echo "chci ZAPSAT:--zapisovaciObjekt -- nove(zvetsene) uid, persisted = true "; // insert CHYBA
//(string)( $zapisovaciO->uidKonfiguraceTestu + 1 );

///* @var $zapisovaciO KonfiguraceTestuRow */
//$zapisovaciO = clone ($rowObjektKonfiguraceTestuAll[array_key_last($rowObjektKonfiguraceTestuAll) ] ); //"clone zkopiruju si objekt"
//$zapisovaciO->uidKonfiguraceTestu =   (string)( $zapisovaciO->uidKonfiguraceTestu + 1 );
//$zapisovaciO->setPersisted(true);
//var_dump($zapisovaciO);
//$daoKonfiguraceTestu->save( $zapisovaciO );

//echo "chci ZAPSAT:--zapisovaciObjekt -- puvodni uid, persisted = true "; //update
//echo "chci ZAPSAT:--zapisovaciObjekt -- puvodni uid, persisted = false "; //update  CHYBA



//
//var_dump("vzato z:");
//var_dump($rowObjektKonfiguraceTestuAll[array_key_last($rowObjektKonfiguraceTestuAll)]);
//$daoKonfiguraceTestu->save( $zapisovaciO zvetsene uid XX$rowObjektKonfiguraceTestuAll[array_key_last($rowObjektKonfiguraceTestuAll)]XX);
//1-- persisted je true - dela update - ale nic nezapise 0 rows (nenajde uid) -
//  !!ale hlasi chybu, a pritom nema nema objekt $e !!


echo "</html>";


