<?php
namespace Test\TestovaciEntityRepositoryTest;

use PHPUnit\Framework\TestCase;

use Model\Entity\Identity\IdentityAbstract;
use Model\Entity\Identity\IdentityInterface;
use Model\Entity\EntityInterface;
use Model\Entity\EntityAbstract;
use Model\Entity\AccessorInterface;

use Model\RowObject\Key\KeyAbstract;
use Model\RowObject\Key\KeyInterface;
use Model\RowObject\RowObjectAbstract;
use Model\RowObject\AttributeInterface;
use Model\RowObject\RowObjectInterface;

use Model\Entity\Identity\Exception\MismatchedIndexesToKeyAttributeFieldsException;
use Model\Hydrator\AccessorHydratorInterface;
use Model\Hydrator\NameHydrator\AccessorNameHydratorInterface;
use Model\Hydrator\NameHydrator\AccessorMethodNameHydratorInterface;
use Model\Hydrator\NameHydrator\AttributeNameHydratorInterface;
use Model\Hydrator\Filter\OneToOneFilterInterface;
use Model\Hydrator\Filter\ColumnFilterInterface;

use Model\Hydrator\OneToOneAccessorHydrator;
use Model\Hydrator\CeleJmenoAccessorHydrator;
use Model\Hydrator\AttributeHydrator;
use Model\Hydrator\AttributeHydratorInterface;

use Model\Dao\DaoAbstract;
use Model\Dao\DaoKeyDbVerifiedInterface;


use Model\RowData\RowDataInterface;
use Model\RowData\RowData;

use Model\Repository\RepoAbstract_vs;

use Test\Configuration\DaoContainerConfigurator;
use Pes\Container\Container;
use Pes\Database\Handler\Handler;
use Pes\Database\Metadata\MetadataProviderMysql;


//---------------------------------------------------------------------------------------
interface TestovaciDaoInterfaceMock   {    
    public function get( $asocPoleKlic ): ?RowDataInterface ;    
    public function insert( RowDataInterface $rowData): void;
    public function update( RowDataInterface $rowData): void;
    public function delete( RowDataInterface $rowData ): void;   
}
/**
 * 'Omezené' Dao.   Constructor v tomto mocku NENI, chybí DaoAbstract.
 */
class TestovaciDaoMock /*extends DaoAbstract*/ implements TestovaciDaoInterfaceMock {
    //class Dao extends \Model\Dao\DaoAbstract implements DaoInterfaceMock {    
    
    // nastaveni "konstant"
    private  $testDateString;
    private  $testDateTimeString;
    private  $hodnotaDate;
    private  $hodnotaDateTime;                    
    
    public function get( $asocPoleKlic ): ?RowDataInterface {
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
class RowObjectMock extends RowObjectAbstract implements AttributeInterface {
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
    public function setUidPrimarniKlicZnaky( string $uidPrimarniKlicZnaky): IdentityMock ;
    public function getUidPrimarniKlicZnaky() :string ;
   
    public function setKeyHash( $testovaciKeyHash ) ;
}
class TestovaciIdentityMock extends IdentityAbstract implements  TestovaciIdentityInterfaceMock {
    private  $uidPrimarniKlicZnaky;
    private  $klic;
    
    public function setUidPrimarniKlicZnaky( string $uidPrimarniKlicZnaky) : IdentityMock{
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
class MethodNameHydratorMock implements AccessorMethodNameHydratorInterface {    
    public function hydrate(string $name): string {
        return 'set' . ucfirst($name);
    }        
    public function extract(string $name): string {       
        return 'get' . ucfirst($name);
    }    
}
//---------------------------------------------------------------------------------------



//---------------------------------------------------------------------------------------
class OneToOneFilterMock implements OneToOneFilterInterface {    
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

 /**
 * Filtr pro hydrator typu RowObjectHydrator 
 */
class ColumnFilterMock implements ColumnFilterInterface {           
    private $poleJmen;               
    public function __construct( array $poleJmen ) {
        $this->poleJmen = $poleJmen;        
    }           
    public function getIterator() : \Traversable {        
         return new \ArrayIterator( $this->poleJmen );
    }             
}
//---------------------------------------------------------------------------------------



class CeleJmenoEntityHydratorMock implements AccessorHydratorInterface {
    public function hydrate( AccessorInterface $entity, AttributeInterface $rowObject): void {        
    }
    public function extract( AccessorInterface $entity, AttributeInterface $rowObject ): void {        
    }
}


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

//---------------------------------------------------------------------------------------
class AccessorMethodNameHydratorMock implements AccessorMethodNameHydratorInterface {    
    public function hydrate(string $name): string {
        return 'set' . ucfirst($name);
    }        
    public function extract(string $name): string {       
        return 'get' . ucfirst($name);
    }    
}

class AccessorNameHydratorMock implements AccessorNameHydratorInterface {   
    public function hydrate( string $name ) : string {   
        return $name  ;        
    }  
    public function extract( string $name )  : string {                
       return  $name  ;                         
    }
}
//---------------------------------------------------------------------------------------


//---------------------------------------------------------------------------------------
class OneToOneAccessorHydratorMock implements AccessorHydratorInterface {
    /**
     *
     * @var AccessorMethodNameHydratorInterface
     */
    private $methodNameHydrator;    
    /**
     * Filtr obsahuje seznam jmen (pole jmen)  vlastností row objektu k hydrataci/extrakci.
     * 
     * @var  OneToOneFilterInterface    -  extends \IteratorAggregate
     */
    private $filter;        
    
    public function __construct ( AccessorMethodNameHydratorInterface $methodNameHydrator,  OneToOneFilterInterface $filter  ) { 
        $this->methodNameHydrator = $methodNameHydrator;
        $this->filter = $filter;       
    }        
     
    /**
     * Hydratuje objekt entity hodnotami z row objectu.
     * 
     * @param AccesorInterface $entity
     * @param AttributeInterface $rowObject
     * @return void
     */
    public function hydrate( AccessorInterface $entity, AttributeInterface $rowObjecta ): void {        
        foreach ( $this->filter as $name ) {      //=> jmeno vlastnosti row objektu        
            $methodName = $this->methodNameHydrator->hydrate( $name );
            $entity->$methodName( $rowObjecta->$name );
        }        
    }    
       
     
    /**
     * Extrahuje hodnoty z objektu entity do row objectu.
     * 
     * @param AccesorInterface $entity
     * @param AttributeInterface $rowObject
     * @return void
     */
    public function extract ( AccessorInterface $entity, AttributeInterface $rowObjecta ): void {       
        foreach ( $this->filter as $name )  {   //=> jmeno vlastnosti row objektu                  
            $methodName = $this->methodNameHydrator->extract( $name );
            $rowObjecta->$name = $entity->$methodName() ;
        }        
    }
    
}


//###########################################################################################################################
//###########################################################################################################################
interface TestovaciEntityRepositoryInterfaceMock {    
    /**
     * 
     * @param AccessorInterface $identity
     * @return IdentityInterfaceMock|null
     */
    public function get (IdentityInterface $identity ): ?TestovaciEntityInterfaceMock;    
    /**
     * 
     * @param AccessorInterface $entity
     * @return void
     */
    public function add( AccessorInterface $entity ): void;        
    /**
     * 
     * @param AccessorInterface $entity 
     * @return void
     */
    public function remove( AccessorInterface $entity  ): void;
}




class TestovaciEntityRepositoryMock extends RepoAbstract_vs implements TestovaciEntityRepositoryInterfaceMock {                         
    // private $identityHydrator;         
    
    public function __construct(            
                                TestovaciDaoInterfaceMock $testovaciDao,  
                                //RowDataInterface $rowData /*docasne*/,
//                                
                                AttributeHydratorInterface $attributeHydrator    /*$rowOjectHydrator*/,                                  
                                AccessorHydratorInterface $accessorHydrator
            
                                //EntityHydratorInterface $celeJmenoEHydrator                                
                                //IdentityHydratorInterface $identityHydrator  
                               ) {                 
        $this->dao = $testovaciDao;     //->dao  -  definovanno v abstractu repository  
        //$this->rowData = $rowData;
                  
        //$this->registerRowObjectHydrator( $rowObjectHydrator); //v abstractu     
        //$this->registerEntityHydrator($celeJmenoEHydrator); 
        $this->registerHydratorObject( $attributeHydrator );   
        $this->registerHydratorEntity( $accessorHydrator ); 
       
    }   
    
   
    
    public function get (   IdentityInterface $identity ): ?TestovaciEntityInterfaceMock {
        $index = $this->indexFromIdentity( $identity );
        if (!isset($this->collection[$index])) {
            
            $rowData = $this->dao->get( $index  ); // vraci konstantni pole - hodnoty z úložistě, $keyHash  zatim neni v metode get pouzito
            //$rowData = new RowDataMock(); 
            
            $this->recreateEntity( $index  , $rowData ); // v abstractu,    zarazeni do collection z uloziste, pod indexem  $index
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
            
    public function add( AccessorInterface $entity ): void {                
//        $index = $this->indexFromEntity($paper);
//        $this->addEntity($paper, $index);
    }
    
    public function remove( AccessorInterface $entity  ): void {                
//        $index = $this->indexFromEntity($paper);
//        $this->removeEntity($entity, $index);
    }
    
    
    //------------------------------------------------
    protected function createRowObj( $key) : AttributeInterface{
        return new RowObjectMock( $key );  
    }
    
    /*konkretni createEntity(),    to  v abstract - nepouzivat*/ 
    /**
     * 
     * @return AccessorInterface
     */
    protected function createEntity( ) : AccessorInterface{
        $identity  = new TestovaciIdentityMock();
        return new TestovaciEntityMock( $identity /*?????.....tady ma byt identita ???*/);
    }



    protected function indexFromRow($row) /*keyHashFromRow*/ {
        return $row['id'];
    }
    
    protected function indexFromIdentity($identity){
        return 'indexFfromIidentity';
    }

    
    
    
//    
////-------------------------------------------------------------------------------------------------       
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



//---------------------------TEST TEST TEST ------------------------------------------------
/**
 * Description of TestovaciEntityRepositoryTest
 *
 * @author vlse2610
 */
class TestovaciEntityRepositoryTest  extends TestCase{
//    private $testovaciKeyHash;
//    private $testovaciAttribute;
//    private $testovaciIdentityM;
    
    private $testovaciDaoM;    
    //private $testovaciRowDataM;
    
    private $poleJmen; 
    private $oneToOneEHydratorM;
    private $celeJmenoEHydratorM;
    private $attributeHydratorM;
    
    protected static  $dbhZContaineru;
    protected static  $container;
            
//--------------------------------------------------------------------------------    
    public function setUp(): void {
        self::$container = (new DaoContainerConfigurator())->configure(new Container());
        self::$dbhZContaineru  = self::$container->get(Handler::class); // $dbh = $container->get(Handler::class); 
        
        $this->testovaciDaoM = new TestovaciDaoMock();        
        //$rowObjectHydrator = new $rowObjectHydratorMock();       
//                    $this->testovaciRowDataM = new RowData( ['Klic1' => 'aaa', 'Klic2' => 'B' , 
//                                                             'prvekVarchar' ,  'prvekChar' , 'jmenoClovek', 'prijmeniClovek' ] );   //        
        
        $this->poleJmen =  [  "prvekVarchar" ,  "prvekChar" , 'jmenoClovek', 'prijmeniClovek'  ] ;   
        $this->oneToOneEHydratorM = new OneToOneAccessorHydratorMock ( new MethodNameHydratorMock(),
                                                                       new OneToOneFilterMock( $this->poleJmen) );       
        //$this->celeJmenoEHydratorM = new CeleJmenoEntityHydratorMock();
       
        /* @var $metaDataProvider MetadataProviderMysql */
        $metaDataProvider = self::$container->get(MetadataProviderMysql::class); 
        $poleJmenAttributes =  [ 
            "prvekChar" , "prvekVarchar", "prvekInteger" ,"prvekText", "prvekBoolean",  
            "prvekDate", "prvekDatetime", "prvekTimestamp" , 'jmenoClovek', 'prijmeniClovek'          ] ;     
        
        $this->attributeHydratorM =  new AttributeHydrator( new AttributeNameHydratorROMock(),  
                                              $metaDataProvider->getTableMetadata('testovaci_table_row'), /* pro zjisteni typu*/
                                              new ColumnFilterMock( $poleJmenAttributes )
                                            ); 
    }        
        
        
    public function testGet() {       
//        $testovaciKeyHash   = [ 'Klic1' => 'aaa', 'Klic2' => 'B'  ];   // [ 'Klic1' => 'aa', 'Klic2' => 'bb'  ];
//        $testovaciAttribute = [ 'Klic1' ,'Klic2'  ];                   // [ 'Klic1' ,'Klic2'  ];              
        $testovaciIdentityM = new TestovaciIdentityMock (  );     //pozn. neni generovany klic v entite   
        // $testovaciIdentityM->setKeyHash( $testovaciKeyHash );
        //------------------
        $testovaciRepository = new TestovaciEntityRepositoryMock( 
                                $this->testovaciDaoM,   
                               // $this->testovaciRowDataM,         
                                $this->attributeHydratorM, 
                                $this->oneToOneEHydratorM                                
        );        
        
        /* get */ 
        $testovaciEntityNovy = $testovaciRepository->get( $testovaciIdentityM ); //vraceno  nove vyrobene         
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











//public function testGet() {       
//        $testovaciKeyHash   = [ 'Klic1' => 'aaa', 'Klic2' => 'B'  ];   // [ 'Klic1' => 'aa', 'Klic2' => 'bb'  ];
//        $testovaciAttribute = [ 'Klic1' ,'Klic2'  ];                   // [ 'Klic1' ,'Klic2'  ];              
//        $testovaciIdentityM = new TestovaciIdentityMock ( $testovaciAttribute );     //pozn. neni generovany klic v entite   
//        $testovaciIdentityM->setKeyHash( $testovaciKeyHash );
//        //------------------
//        $testovaciRepository = new TestovaciEntityRepositoryMock( 
//                                $this->testovaciDaoM,   
//                               // $this->testovaciRowDataM,         
//                                $this->attributeHydratorM, 
//                                $this->oneToOneEHydratorM                                
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