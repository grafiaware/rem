<?php
namespace Test\TestovaciEntityRepositoryTest;

use PHPUnit\Framework\TestCase;

use Model\Entity\Identity\IdentityAbstract;
use Model\Entity\Identity\IdentityInterface;
use Model\Entity\EntityInterface;
use Model\Entity\EntityAbstract;
use Model\Entity\AccessorInterface;
//use Model\Entity\Identity\Exception\MismatchedIndexesToKeyAttributeFieldsException;

use Model\RowObject\Key\KeyAbstract;
use Model\RowObject\Key\KeyInterface;
use Model\RowObject\RowObjectAbstract;
use Model\RowObject\AttributeInterface;
use Model\RowObject\RowObjectInterface;
use Model\RowObject\RowObjectManagerInterface;

use Model\Hydrator\NameHydrator\AccessorNameHydrator;
use Model\Hydrator\NameHydrator\AccessorMethodNameHydrator;
use Model\Hydrator\NameHydrator\AttributeNameHydrator;

use Model\Filter\OneToOneFilter;
use Model\Filter\ColumnFilter;
use Model\Filter\OneToManyFilter;

use Model\Hydrator\AccessorHydratorInterface;
use Model\Hydrator\OneToOneAccessorHydrator;
use Model\Hydrator\CeleJmenoAccessorHydrator;
use Model\Hydrator\AttributeHydrator;
use Model\Hydrator\AttributeAccessHydratorInterface;
use Model\Hydrator\CeleJmenoGluer;


use Model\Dao\DaoAbstract;
use Model\Dao\DaoKeyDbVerifiedInterface;
use Model\Dao\DaoInterface;

use Model\RowData\RowDataInterface;
use Model\RowData\RowData;

use Model\Repository\RepoAbstract_vs;
use Model\Repository\RepositoryInterface;

use Test\Configuration\DaoContainerConfigurator;
use Pes\Container\Container;
use Pes\Database\Handler\Handler;
use Pes\Database\Metadata\MetadataProviderMysql;


//---------------------------------------------------------------------------------------
interface TestovaciDaoInterfaceMock   /*extends DaoInterface */  {    
    public function get( /* $asocPoleKlic */ ): ?RowDataInterface ;    
    public function insert( RowDataInterface $rowData): void;
    public function update( RowDataInterface $rowData): void;
    public function delete( RowDataInterface $rowData ): void;   
}
/**
 * 'Omezené' Dao.   Constructor v tomto mocku NENI, chybí DaoAbstract.
 */
class TestovaciDaoMock extends DaoAbstract implements TestovaciDaoInterfaceMock {
    //class Dao extends \Model\Dao\DaoAbstract implements DaoInterfaceMock {    
    
    // nastaveni "konstant"
    private  $testDateString;
    private  $testDateTimeString;
    private  $hodnotaDate;
    private  $hodnotaDateTime;                    
    
    public function get( /*$asocPoleKlic*/ ): ?RowDataInterface {
        // $asocPoleKlic nepotrebuju v omezenem Dao na nic
        $this->testDateString = "2010-09-08";
        $this->hodnotaDate = \DateTime::createFromFormat("Y-m-d", $this->testDateString = "2010-09-08" )->setTime(0,0,0,0); 
        $this->testDateTimeString = "2005-06-07 22:23:24";
        $this->hodnotaDateTime = \DateTime::createFromFormat("Y-m-d H:i:s", $this->testDateTimeString );    
    
        $rowData =  new RowData( [  "uid_primarni_klic_znaky" => "KEYklic",
                                    "klic" => "",             
//                                            "cele_jmeno" => "", 
                                           "jmeno_clovek" => "Viktor", 
                                           "prijmeni_clovek" => "Desátý", 
//                                            "titul_pred" => "Mgr.", 
//                                            "jmeno" => "", 
//                                            "prijmeni" => "", 
//                                            "titul_za" => "",                         
                                    "prvek_char" => "QWERTZ",                               
                                    "prvek_varchar" => "Qěščřžýáíé",
                                    "prvek_integer" => 111,
                                    "prvek_text" => "Povídám pohádku",
                                    "prvek_boolean" => true,
                                    "prvek_date" =>  $this->testDateString,
                                    "prvek_datetime" => $this->testDateTimeString,
                                    "prvek_timestamp" => $this->testDateTimeString  ] );                    
        return $rowData;
    }
    public function insert( RowDataInterface $rowData): void {}
    
    public function update( RowDataInterface $rowData): void {}
    
    public function delete( RowDataInterface $rowData ): void {}            
}
//---------------------------------------------------------------------------------------



//---------------------------------------------------------------------------------------
interface KeyInterfaceMock extends KeyInterface{    
}
class KeyMock extends KeyAbstract implements KeyInterfaceMock {
    public $uidPrimarniKlicZnaky;    
     //v Abstract  public $generated?
}
//---------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------
//interface RowObjectInterfaceMock extends RowObjectInterface {
//    
//}
class RowObjectMock extends RowObjectAbstract implements RowObjectInterface {
    public $celeJmeno;        
    public $jmenoClovek;
    public $prijmeniClovek;
    
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
//---------------------------------------------------------------------------------------



//---------------------------------------------------------------------------------------
interface TestovaciIdentityInterfaceMock extends IdentityInterface{
    public function setUidPrimarniKlicZnaky( string $uidPrimarniKlicZnaky): TestovaciIdentityMock ;
    public function getUidPrimarniKlicZnaky() :string ;
   
    public function setKeyHash( $testovaciKeyHash ) ;
}
class TestovaciIdentityMock extends IdentityAbstract implements  TestovaciIdentityInterfaceMock {
    protected $hash;
    
    private  $uidPrimarniKlicZnaky;
    private  $klic;
    
    
    public function __construct ( array $hash  ) {        
        $this->hash = $hash;
        
        
    }
    
    public function setUidPrimarniKlicZnaky( string $uidPrimarniKlicZnaky) : TestovaciIdentityMock {
        $this->uidPrimarniKlicZnaky = $uidPrimarniKlicZnaky;
        return $this;
    }
    public function getUidPrimarniKlicZnaky() :string {
        return $this->uidPrimarniKlicZnaky;
    }        
    
    public  function setKeyHash( $testovaciKeyHash )  {
        $this->hash = $testovaciKeyHash;
    }
}
//---------------------------------------------------------------------------------------

    

//---------------------------------------------------------------------------------------
interface TestovaciEntityInterfaceMock extends EntityInterface {    
        public function getCeleJmeno();        
        public function getJmenoClovek();
        public function getPrijmeniClovek();
        
        public function getPrvekVarchar();
        public function getPrvekChar();
        public function getPrvekText();
        public function getPrvekInteger();
        public function getPrvekDate(): \DateTime;
        public function getPrvekDatetime(): \DateTime;
        public function getPrvekTimestamp(): \DateTime;
        public function getPrvekBoolean();     

        public function setCeleJmeno( string $celeJmeno) :TestovaciEntityInterfaceMock;               
        public function setJmenoClovek($jmenoClovek) :TestovaciEntityInterfaceMock;
        public function setPrijmeniClovek($prijmeniClovek) :TestovaciEntityInterfaceMock;        
        
        public function setPrvekVarchar($prvekVarchar) :TestovaciEntityMock;
        public function setPrvekChar($prvekChar) :TestovaciEntityMock;
        public function setPrvekText($prvekText) :TestovaciEntityMock;
        public function setPrvekInteger($prvekInteger) :TestovaciEntityMock;
        public function setPrvekDate(\DateTime $prvekDate) :TestovaciEntityMock;
        public function setPrvekDatetime(\DateTime $prvekDatetime) :TestovaciEntityMock;
        public function setPrvekTimestamp(\DateTime $prvekTimestamp) :TestovaciEntityMock;
        public function setPrvekBoolean($prvekBoolean) :TestovaciEntityMock;        
}
class TestovaciEntityMock extends EntityAbstract implements  TestovaciEntityInterfaceMock {      
    //V EntityAbstract JE Identity
        /**
         * @var string
         */   
        private $celeJmeno;           
        private $jmenoClovek;
        private $prijmeniClovek;
        
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
        
    //----------------------------------------------------- 
        public function getCeleJmeno() : string {
            return $this->celeJmeno;
        }    
        public function getJmenoClovek(): string {
            return $this->jmenoClovek;
        }        
        public function getPrijmeniClovek(): string {
            return $this->prijmeniClovek;
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
        public function setCeleJmeno( string $celeJmeno) : TestovaciEntityInterfaceMock {
           $this->celeJmeno = $celeJmeno;
           return $this;
        }      
        public function setJmenoClovek($jmenoClovek) :TestovaciEntityInterfaceMock{
            $this->jmenoClovek = $jmenoClovek;
            return $this;
        }
        public function setPrijmeniClovek($prijmeniClovek) :TestovaciEntityInterfaceMock{
            $this->prijmeniClovek = $prijmeniClovek;
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
//---------------------------------------------------------------------------------------





//---------------------------------------------------------------------------------------
//class RowObjectManagerMock implements RowObjectManagerInterface {
//    
//    public $hydratorsObject;
//    
//    public function __construct(  dao ) {
//        
//    }
//    
//    public function getRowObject() : RowObjectInterface {
//            $o = new RowObjectMock( new KeyMock( [] ));
//            return  $o;
//    }
//    
//    function registerHydratorObject(  AttributeHydratorInterface $hydrator) {
//        $this->hydratorsObject[] = $hydrator;
//    }
//    
//    public function add ( RObject ) {
//      //extract RObject, Key do new Rowdata
//      //Dao->insert (Rowdata)
//      //na konci v dao je obcerstveny Rowdata)   
//      //hydrate RObject
//    }
//    public function get (Key){
//      //extract  Key do new Rowdata
//      //Dao->get (Rowdata)
//      //na konci v dao je obcerstveny Rowdata) 
//      //hydrate do new RowObject
//    }
//}





//###########################################################################################################################
//################################## R E P O S I T O R Y ####################################################################
//###########################################################################################################################

interface TestovaciEntityRepositoryInterfaceMock extends RepositoryInterface {    
    /**
     * repository
     * @param AccessorInterface $identity
     * @return IdentityInterfaceMock|null
     */   
    public function get (IdentityInterface $identity ): ?TestovaciEntityInterfaceMock;    
    /**
     * repository
     * @param AccessorInterface $entity
     * @return void
     */
    public function add( AccessorInterface $entity ): void;        
    /**
     * repository
     * @param AccessorInterface $entity 
     * @return void
     */
    public function remove( AccessorInterface $entity  ): void;
}




class TestovaciEntityRepositoryMock extends RepoAbstract_vs implements TestovaciEntityRepositoryInterfaceMock {                         
    
    public function __construct(            
                                TestovaciDaoInterfaceMock $testovaciDao,  
                                //RowDataInterface $rowData /*docasne*/,//                              
                                RowObjectManagerInterface $rowObjectManager,            
                                //AttributeHydratorInterface $attributeHydrator    /*$rowOjectHydrator*/,                  
            
                                AccessorHydratorInterface $accessorHydrator,            
                                AccessorHydratorInterface $celeJmenoEHydrator                                
                                //IdentityHydratorInterface $identityHydrator  
                               ) {                 
        $this->dao = $testovaciDao;     //->dao  -  definovanno v abstractu repository      
        $this->rowObjectManager = $rowObjectManager;  //->rowObjectManager -  definovanno v abstractu repository  
                  
        //$this->registerEntityHydrator($celeJmenoEHydrator); 
        //$this->registerHydratorObject( $attributeHydrator );   
        $this->registerHydratorEntity( $accessorHydrator ); 
        $this->registerHydratorEntity( $celeJmenoEHydrator );
       
    }   
    
   
    // * repository
    public function get ( IdentityInterface $identity ): ?TestovaciEntityInterfaceMock {
        $index = $this->indexFromIdentity( $identity );
        if (!isset($this->collection[$index])) {
            
 //        /*ale nekde v ROManageru*/   $rowData = $this->dao->insert(   ); //---???????? vraci konstantni pole - hodnoty z úložistě, $keyHash  zatim neni v metode get pouzito
            
            //foreach $this->hydratorsEnti as $hydrator                       
            
            /** @var RowObjectInterface $rowObject*/
            $rowObject = $this->rowObjectManager->get( $rowObject);
            
            
            //$this->recreateEntity( $index  , $rowObject ); // v abstractu,    zarazeni do collection v uloziste?rowObjectu, pod indexem  $index
        }        
        return $this->collection[$index] ?? NULL;                                //            
    }    
    
//     public function getByReference($menuItemIdFk): ?EntityInterface {
//        $row = $this->dao->getByFk($menuItemIdFk);
//        $index = $this->indexFromRow($row);
//        if (!isset($this->collection[$index])) {
//            $this->recreateEntity($index, $row);
//        }
//        return $this->collection[$index] ?? NULL;
//    }

    // * repository
    public function add( AccessorInterface $entity ): void {                
//        $index = $this->indexFromEntity();
        
//        /*ale nekde v ROManageru*/   $rowData = $this->dao->insert(   ); //---???????? vraci konstantni pole - hodnoty z úložistě, $keyHash  zatim neni v metode get pouzito

        
         //foreach $this->hydratorsEnti as $hydrator 
         // $rowObject = $this->rowObjectManager->add( $rowObject);
         // 
         //  $hydrator->hydrate(  $entity   , $rowObject)
          
    }
    
    // * repository    
//    public function remove( AccessorInterface $entity  ): void {                
//        $index = $this->indexFromEntity($paper);
//        $this->removeEntity($entity, $index);
    }
    
    
    //------------------------------------------------
    // * repository
    protected function createRowObj( $key) : AttributeInterface{
        return new RowObjectMock( $key );  
   }   
    // * repository 
    /** 
     * konkretni createEntity(),    to  v abstract - nepouzivat     
     * @return TestovaciEntityInterfaceMock
     */
    protected function createEntity( ) : TestovaciEntityInterfaceMock{
        $identity  = new TestovaciIdentityMock( [] );
        
        return new TestovaciEntityMock( $identity /*?????.....tady ma byt identita ???*/);
    }

    // * repository
    protected function indexFromRow($row) /*keyHashFromRow*/ {
        return $row['id'];
    }
    // * repository   
    protected function indexFromIdentity( TestovaciIdentityMock $identity){
        $indexZIdentity = $identity->getUidPrimarniKlicZnaky() . '_Index';        
        //return 'indexFfromIidentity';
        return $indexZIdentity;
    }

    
    
    
//    
////----------------------------------------neidentifikovane pokusy o cosi---------------------------------------------------------       
//    /**
//     * 
//     * @param type $identity
//     * @return EntityInterface|null
//     */
//    public function get( IdentityInterface $identity ): ?EntityInterface {     
//       
//        foreach ($this->entities as $entity) {
//            if ($entity->getIdentity()->isEqual($identity)) {
//                return $entity;
//            }
//        }
//        //nova entity
//        $entity = new TestovaciEntity( );
//        $entity->setIdentity($identity);
//        
//        /* ?? */$rowObject = $this->dao->get($identity->getKeyHash());     ///????????????????????????????identity
//        
//        $this->oneToOneEHydrator->hydrate($entity, $rowObject);
//        $this->celeJmenoEHydrator->hydrate($entity, $rowObject);
//        
//        $this->entities[] = $entity;
//        return $entity;
//     //---------------------------------------------------   
////        /* @var $rowObject RowObjectInterface */   
////       $rowObject = new RowObject (); 
////       $this->dao->get(  $this->identityHydrator->extract( $identity, $rowObject ) );              
////       if ($rowObject) {
////           $identity2 = new Identity();
////           $this->identityHydrator->hydrate( $identity2, $rowObject);
////           
////           /* @var $entity TestovaciEntityInterface */
////           $entity = new TestovaciEntity;                               
////           $this->celeJmenoEHydrator->hydrate($entity, $rowObject);
////           $this->oneToOneEHydrator->hydrate($entity, $rowObject );           
////       }
////       else {          }
////                     
////       if  ( $identity->isEqual($identity2)) {           
////       }
////       else {           
////       }     
////       return $entity ;        
//    } 
//    public function add( EntityInterface $entity): void {
//        
////        $rowObject = new TestovaciRowObject();
////        $this->oneToOneEHydrator->extract($entity, $rowObject);
////        $this->celeJmenoEHydrator->extract($entity, $rowObject);
////        
////        $this->dao->save($rowObject);        
//    }
//    public function remove( EntityInterface $entity ): void {        
//        ;
//    }
    
    
}

//###########################################################################################################################
//######################################### TEST TEST TEST ##################################################################
//------------------------------------------TEST TEST TEST ------------------------------------------------
/**
 * Description of TestovaciEntityRepositoryTest
 *
 * @author vlse2610
 */
class TestovaciEntityRepositoryTest  extends TestCase{
//    private $testovaciKeyHash;
//    private $testovaciAttribute;
//    private $testovaciIdentityM;
        //private $testovaciRowDataM;
    
    private $poleJmen; 
    
    protected static  $dbhZContaineru;
    protected static  $container;
            
    /**
     *
     * @var TestovaciEntityRepositoryMock 
     */
    private $repository;
//--------------------------------------------------------------------------------    
    public function setUp(): void {
        self::$container = (new DaoContainerConfigurator())->configure(new Container());
        self::$dbhZContaineru  = self::$container->get(Handler::class); // $dbh = $container->get(Handler::class); 
        
        $testovaciDaoM = new TestovaciDaoMock();        
        
        $this->poleJmen =  [  "prvekVarchar" ,  "prvekChar"  ] ;   
        $oneToOneEHydratorM = new OneToOneAccessorHydrator( new AccessorMethodNameHydrator(),
                                                            new OneToOneFilter( $this->poleJmen ) );   
        $this->poleJmen =  [  'jmenoClovek', 'prijmeniClovek'  ] ; 
        $celeJmenoEHydratorM = new CeleJmenoAccessorHydrator( new AccessorNameHydrator(), new AccessorMethodNameHydrator(),
                                                              new OneToManyFilter( $this->poleJmen ), new CeleJmenoGluer() );        
        /* @var $metaDataProvider MetadataProviderMysql */
        $metaDataProvider = self::$container->get(MetadataProviderMysql::class); 
        $poleJmenAttributes =  [ 
            "prvekChar" , "prvekVarchar", "prvekInteger" ,"prvekText", "prvekBoolean",  
            "prvekDate", "prvekDatetime", "prvekTimestamp" , 'jmenoClovek', 'prijmeniClovek'          ] ;             
        $attributeHydratorM =  new AttributeHydrator( 
                                      new AttributeNameHydrator(),  
                                      $metaDataProvider->getTableMetadata('testovaci_table_row'), /* pro zjisteni typu*/
                                      new ColumnFilter( $poleJmenAttributes )                   ); 
        
        $rowObjectManagerM =  new RowObjectManagerMock( $testovaciDaoM );
        $rowObjectManagerM->registerHydratorObject( $attributeHydratorM );
        
        $this->repository = new TestovaciEntityRepositoryMock( 
                                    //$testovaciDaoM,   
                                    $rowObjectManagerM,    
                                    //$attributeHydratorM, 
                                    $oneToOneEHydratorM, 
                                    $celeJmenoEHydratorM
        );           
        
    }        
        
        
    public function testGet() {       
//        $testovaciKeyHash   = [ 'Klic1' => 'aaa', 'Klic2' => 'B'  ];   // [ 'Klic1' => 'aa', 'Klic2' => 'bb'  ];
//        $testovaciAttribute = [ 'Klic1' ,'Klic2'  ];                   // [ 'Klic1' ,'Klic2'  ];    
        
        $testovaciIdentityM = new TestovaciIdentityMock ( [ "uid_primarni_klic_znaky",  "klic"] );     //pozn. neni generovany klic v entite   
        $testovaciIdentityM->setUidPrimarniKlicZnaky( "KEYklic" ); // "uid_primarni_klic_znaky" => "KEYklic","klic" => "",  
        
        
     
        //------------------        
        /* get */ 
        $testovaciEntityNovy = $this->repository->get( $testovaciIdentityM ); //vraceno  nove vyrobene         
        $this->assertInstanceOf( TestovaciEntityInterfaceMock::class, $testovaciEntityNovy );        /*  ocekavana,*/  /*aktualni (nejak vyrobena) */        
                
    }

   
    
    
    public function testAdd() {
//        $testovaciEntity = new TestovaciEntityMock ( $this->testovaciIdentityM );        
//        $this->assertInstanceOf(IdentityInterface::class, $entity->getIdentity());        
    }       
    
    
    public function testRemove() {
//        $entity = new TestovaciEntityMock ( $this->identity );       
//        $this->assertInstanceOf(IdentityInterface::class, $entity->getIdentity());        
    } 
    
}


//---------------------------------------------------------------------------------------------------------------------


//zde si dam vyhozene hydratory a filtry -


//---------------------------------------------------------------------------------------
//class OneToOneFilter implements OneToOneFilterInterface {    
//    /**
//     * @var array
//     */
//    private $poleJmen;   
//    
//    public function __construct(  array $poleJmen ) { 
//        $this->poleJmen = $poleJmen; 
//    }
//    //Pozn. - getIterator vrací iterovatelný objekt.        
//    public function getIterator() : \Traversable{        
//        return new \ArrayIterator(  $this->poleJmen   );
//    }        
//}

 /**
 * Filtr pro hydrator typu RowObjectHydrator 
 */
//class ColumnFilterMock implements ColumnFilterInterface {           
//    private $poleJmen;               
//    public function __construct( array $poleJmen ) {
//        $this->poleJmen = $poleJmen;        
//    }           
//    public function getIterator() : \Traversable {        
//         return new \ArrayIterator( $this->poleJmen );
//    }             
//}
//---------------------------------------------------------------------------------------




//
////---------------------------------------------------------------------------------------
//class AttributeNameHydratorROMock implements AttributeNameHydratorInterface {    
//    public function hydrate(/*$underscoredName*/ $camelCaseName ){
//        //return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $underscoredName))));
//        return strtolower(preg_replace('/(?<!^)([A-Z])/', '_$1', $camelCaseName));
//    }
//
//    public function extract( /*$underscoredName*/ $camelCaseName ) {                
//       //$s2 = strtolower(preg_replace('/(?<!^)([A-Z])/', '_$1', $camelCaseName));
//       return strtolower(preg_replace('/(?<!^)([A-Z])/', '_$1', $camelCaseName));  
//       //return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $underscoredName))));
//    }
//}
////---------------------------------------------------------------------------------------
//class AccessorMethodNameHydratorMock implements AccessorMethodNameHydratorInterface {    
//    public function hydrate(string $name): string {
//        return 'set' . ucfirst($name);
//    }        
//    public function extract(string $name): string {       
//        return 'get' . ucfirst($name);
//    }    
//}
//
//class AccessorNameHydratorMock implements AccessorNameHydratorInterface {   
//    public function hydrate( string $name ) : string {   
//        return $name  ;        
//    }  
//    public function extract( string $name )  : string {                
//       return  $name  ;                         
//    }
//}
////---------------------------------------------------------------------------------------


//---------------------------------------------------------------------------------------
//class CeleJmenoEntityHydratorMock implements AccessorHydratorInterface {
//    public function hydrate( AccessorInterface $entity, AttributeInterface $rowObject): void {        
//    }
//    public function extract( AccessorInterface $entity, AttributeInterface $rowObject ): void {        
//    }
//}
//---------------------------------------------------------------------------------------
//class OneToOneAccessorHydratorMock implements AccessorHydratorInterface {
//    /**
//     *
//     * @var AccessorMethodNameHydratorInterface
//     */
//    private $methodNameHydrator;    
//    /**
//     * Filtr obsahuje seznam jmen (pole jmen)  vlastností row objektu k hydrataci/extrakci.
//     * 
//     * @var  OneToOneFilterInterface    -  extends \IteratorAggregate
//     */
//    private $filter;        
//    
//    public function __construct ( AccessorMethodNameHydratorInterface $methodNameHydrator,  OneToOneFilterInterface $filter  ) { 
//        $this->methodNameHydrator = $methodNameHydrator;
//        $this->filter = $filter;       
//    }        
//     
//    /**
//     * Hydratuje objekt entity hodnotami z row objectu.
//     * 
//     * @param AccesorInterface $entity
//     * @param AttributeInterface $rowObject
//     * @return void
//     */
//    public function hydrate( AccessorInterface $entity, AttributeInterface $rowObjecta ): void {        
//        foreach ( $this->filter as $name ) {      //=> jmeno vlastnosti row objektu        
//            $methodName = $this->methodNameHydrator->hydrate( $name );
//            $entity->$methodName( $rowObjecta->$name );
//        }        
//    }    
//            
//    /**
//     * Extrahuje hodnoty z objektu entity do row objectu.
//     * 
//     * @param AccesorInterface $entity
//     * @param AttributeInterface $rowObject
//     * @return void
//     */
//    public function extract ( AccessorInterface $entity, AttributeInterface $rowObjecta ): void {       
//        foreach ( $this->filter as $name )  {   //=> jmeno vlastnosti row objektu                  
//            $methodName = $this->methodNameHydrator->extract( $name );
//            $rowObjecta->$name = $entity->$methodName() ;
//        }        
//    }
//    
//}





//--------------------------------------------------------------------------------------------------------------------

//public function testGet() {       
//        $testovaciKeyHash   = [ 'Klic1' => 'aaa', 'Klic2' => 'B'  ];   // [ 'Klic1' => 'aa', 'Klic2' => 'bb'  ];
//        $testovaciAttribute = [ 'Klic1' ,'Klic2'  ];                   // [ 'Klic1' ,'Klic2'  ];              
//        $testovaciIdentityM = new TestovaciIdentityMock ( $testovaciAttribute );     //pozn. neni generovany klic v entite   
//        $testovaciIdentityM->setKeyHash( $testovaciKeyHash );
//        //------------------
//        $testovaciRepository = new TestovaciEntityRepositoryMock( 
//                                $testovaciDaoM,   
//                               // $this->testovaciRowDataM,         
//                                $attributeHydratorM, 
//                                $oneToOneEHydratorM                                
//        );        
//        
//        /* get */ 
//        $testovaciEntityNovy = $testovaciRepository->get( $testovaciIdentityM ); //vraceno  nove vyrobene         
//        $this->assertInstanceOf( TestovaciEntityInterfaceMock::class, $testovaciEntityNovy );        /*  ocekavana,*/  /*aktualni (nejak vyrobena) */        
//        //$this->assertEquals( $this->testovaciIdentityM, $testovaciEntityNovy->getIdentity());   zbytecne    
//        $this->assertTrue( $testovaciEntityNovy->isPersisted() );
//                
//        /* get  */
//        $testovaciEntityStavajici = $testovaciRepository->get( $testovaciIdentityM ); //vraceno  z repozitory (stavajici)        
//        $this->assertInstanceOf( TestovaciEntityInterfaceMock::class, $testovaciEntityStavajici );    /*  ocekavana,*/  /*aktualni (nejak vyrobena) */        
//        //$this->assertEquals( $this->testovaciIdentityM, $testovaciEntityStavajici->getIdentity());   zbytecne   
//        $this->assertTrue( $testovaciEntityStavajici->isPersisted() );
//                
//        $this->assertEquals( $testovaciEntityNovy, $testovaciEntityStavajici );  
//                
//        //chceme testovat 'identickou totoznost objektu'  v  obou obdrzenych promennych - 1 instance objektu, dve instance odkazu na objekt ( 2 promenné )
//        $this->assertTrue($testovaciEntityNovy == $testovaciEntityStavajici)  ;  //ok
//        $this->assertTrue($testovaciEntityNovy === $testovaciEntityStavajici)  ; //ok
//        //jde o stale týž identicky objekt, zde pristupny ze dvou promennych
//        $testovaciEntityNovy->setPrvekChar('y');            //nastavuji stále v jednom objektu
//        $testovaciEntityStavajici->setPrvekVarchar('YYY');
//        $this->assertTrue($testovaciEntityNovy == $testovaciEntityStavajici)  ;  //ok
//        $this->assertTrue($testovaciEntityNovy === $testovaciEntityStavajici)  ; //ok                  
//        
//         
//         
//        //* -------------------obracene poradi v zápisu prvků  klice keyhash  */
//        $testovaciKeyHashObraceny   = [  'Klic2' => 'B', 'Klic1' => 'aaa'  ];   //  v opacnem poradi
//        $testovaciAttributeObraceny = [  'Klic2', 'Klic1'   ];                         
//        $testovaciIdentityM = new Identity ( $testovaciAttributeObraceny  );     //pozn. neni generovany klic v entite   
//        $testovaciIdentityM->setKey(  $testovaciKeyHashObraceny  );
//         
//        /* get  */
//        $testovaciEntityStavajici = $testovaciRepository->get( $testovaciIdentityM ); //vraceno  z repozitory (má být stavajici)   
//        $this->assertTrue( $testovaciEntityStavajici->isPersisted() );
//        $this->assertInstanceOf( TestovaciEntityInterfaceMock::class, $testovaciEntityStavajici );    /*  ocekavana,*/  /*aktualni (nejak vyrobena) */        
//        
//        //$this->assertEquals( $testovaciIdentityM, $testovaciEntityStavajici->getIdentity());          
//        //$this->assertTrue ( $testovaciIdentityM === $testovaciEntityStavajici->getIdentity() ) ;
//        
//        $this->assertTrue( $testovaciEntityNovy == $testovaciEntityStavajici, '***porovnani bezne - neni equal objekt***');  //--porovnani 'bezne' , 
//        // identity maji pořadí zápisu prvků  pole keyhashe  prohozené
//        // ->neni equal 
//  
//        $this->assertTrue( $testovaciEntityNovy === $testovaciEntityStavajici, '***není ten samý (identický) objekt***');
//        // chceme dostat stejny objekt,  identické
//        
//                
////        $this->assertEquals( $testovaciEntityNovy, $testovaciEntityStavajici );   
////        $this->assertTrue($testovaciEntityNovy == $testovaciEntityStavajici)  ;  //ok
////        $this->assertTrue($testovaciEntityNovy === $testovaciEntityStavajici)  ; //ok         
//         
//         //----------------------------------------------------------------------------------------------
//         //priklad se dvema ruznymi instancemi objektu
//         //ekvivalentni
//         $a = new TestovaciEntityMock( $testovaciIdentityM );
//         $b = new TestovaciEntityMock( $testovaciIdentityM );        
//         $this->assertEquals($a, $b);           
//         $this->assertTrue($a == $b)  ;
//         
//         //neekvivalentni
//         $a->setPrvekChar('AAA');         
//         $b->setPrvekChar('BBB');
//         $this->assertNotEquals($a, $b);           
//         $this->assertFalse($a == $b)  ;         
//         
//         //neidenticke
//         $a = new TestovaciEntityMock( $testovaciIdentityM );
//         $a->setPrvekChar('AAA');
//         $b = new TestovaciEntityMock( $testovaciIdentityM );
//         $b->setPrvekChar('AAA');
//         $this->assertEquals($a, $b);           
//         $this->assertTrue($a == $b);
//         $this->assertNotTrue($a === $b);
//         
//                                   
////        // toto je test, ale na identitu         
////        $this->testovaciKeyHash   = [ 'Klic1' => 'aaa', 'Klic2' => 'B'  ];   // [ 'Klic1' => 'aa', 'Klic2' => 'bb'  ];
////        $this->testovaciAttribute = [ 'Klic1' ,'Klic1'  ];                   // [ 'Klic1' ,'Klic2'  ];              
////        $this->testovaciIdentityM = new Identity ( $this->testovaciAttribute );     //pozn. neni generovany klic v entite   
////        $this->expectException( MismatchedIndexesToKeyAttributeFieldsException::class );
////        $this->testovaciIdentityM->setKeyHash( $this->testovaciKeyHash );
//        
//        
//        
//        //----------------------------------------------------
//        //---------------------------------------------------- ma  byt moznost identity s klici 'prazdnymi'   ???
//        $testovaciKeyHash   = [  ];  
//        $testovaciAttribute = [  ];  
//        $testovaciIdentityM = new Identity ( $testovaciAttribute );     //pozn. neni generovany klic v entite   
//        $testovaciIdentityM->setKey( $testovaciKeyHash );
//        
//        /* get  */
//        $testovaciEntityNovy = $testovaciRepository->get( $testovaciIdentityM ); //vraceno  nove vyrobene         
//        $this->assertInstanceOf( TestovaciEntityInterfaceMock::class, $testovaciEntityNovy );           /*  ocekavana,*/  /*aktualni (nejak vyrobena) */        
//        //$this->assertEquals( $this->testovaciIdentityM, $testovaciEntityNovy->getIdentity());    zbytecne    
//        $this->assertTrue( $testovaciEntityNovy->isPersisted() );
//                
//        /* get  */
//        $testovaciEntityStavajici = $testovaciRepository->get( $testovaciIdentityM ); //vraceno  z repozitory (stavajici)        
//        $this->assertInstanceOf( TestovaciEntityInterfaceMock::class, $testovaciEntityStavajici );       /*  ocekavana,*/  /*aktualni (nejak vyrobena) */        
//        //$this->assertEquals( $this->testovaciIdentityM, $testovaciEntityStavajici->getIdentity());   zbytecne   
//        $this->assertTrue( $testovaciEntityStavajici->isPersisted() );
//                
//        $this->assertEquals( $testovaciEntityNovy, $testovaciEntityStavajici );  
//        
//        $this->assertTrue($testovaciEntityNovy == $testovaciEntityStavajici)  ;  //ok
//        $this->assertTrue($testovaciEntityNovy === $testovaciEntityStavajici)  ; //ok
//  
//        
//        
//    }        
//        