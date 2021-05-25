<?php
namespace Test\TestovaciEntityRepositoryTest;

use PHPUnit\Framework\TestCase;

use Model\Entity\Identity\Identity;
use Model\Entity\Identity\IdentityInterface;
use Model\Entity\Identity\Exception\MismatchedIndexesToKeyAttributeFieldsException;
use Model\Entity\Identity\Hydrator\IdentityHydrator;
use Model\Entity\Identity\Hydrator\IdentityHydratorInterface;

use Model\Entity\EntityInterface;
use Model\Entity\EntityAbstract;
use Model\Entity\Hydrator\EntityHydratorInterface;
use Model\Entity\Hydrator\OneToOneEntityHydrator;
use Model\Entity\Hydrator\CeleJmenoEntityHydrator;

use Model\RowObject\RowObjectAbstract;
use Model\RowObject\RowObjectInterface;
//use Model\RowObject\Hydrator\RowObjectHydrator;
use Model\RowObject\Hydrator\RowObjectHydratorInterface;

use Model\RowData\RowDataInterface;
use Model\RowData\RowData;
//use Model\Dao\DaoAbstract;

use Model\Repository\RepositoryAbstract;

use Model\Entity\Hydrator\NameHydrator\MethodNameHydratorInterface;
use Model\Entity\Hydrator\Filter\OneToOneFilterInterface;



interface TestovaciDaoInterfaceMock {    
    public function get( $asocPoleKlic ): ?RowDataInterface ;
    
    public function insert( RowDataInterface $rowData): void;
    public function update( RowDataInterface $rowData): void;
    public function delete( RowDataInterface $rowData ): void;
}
/**
 * 'Omezené' Dao.
 * Constructor v tomto mocku NENI, chybí DaoAbstract.
 */
class TestovaciDaoMock /*extends DaoAbstract*/ implements TestovaciDaoInterfaceMock {
    //class Dao extends \Model\Dao\DaoAbstract implements DaoInterfaceMock {    
    
    public function get( $asocPoleKlic ): ?RowDataInterface {
        // $asocPoleKlic nepotrebuju v omezenem Dao na nic
        
        $rowData = new RowData(  [ 'Klic1' => 'aaa', 'Klic2' => 'B' , 'prvekVarchar' , 'prvekChar', 'jmenoCloveka'=> 'Jája', 'prijmeniCloveka'=>'Čtvrtek' ] );       
        return $rowData;
    }
    public function insert( RowDataInterface $rowData): void {}
    public function update( RowDataInterface $rowData): void {}
    public function delete( RowDataInterface $rowData ): void {}    
}





interface TestovaciEntityInterfaceMock extends EntityInterface {    
        public function getCeleJmeno();
        public function getPrvekVarchar();
        public function getPrvekChar();
        public function getJmenoClovek();
        public function getPrijmeniClovek();
// public function getPrvekText();//        public function getPrvekInteger();//        public function getPrvekDate(): \DateTime;//        public function getPrvekDatetime(): \DateTime;//        public function getPrvekTimestamp(): \DateTime;//        public function getPrvekBoolean();        
        public function setCeleJmeno( string $celeJmeno) :TestovaciEntityInterfaceMock;
        public function setPrvekVarchar($prvekVarchar) :TestovaciEntityInterfaceMock;
        public function setPrvekChar($prvekChar) :TestovaciEntityInterfaceMock;
        public function setJmenoClovek($jmenoClovek) :TestovaciEntityInterfaceMock;
        public function setPrijmeniClovek($prijmeniClovek) :TestovaciEntityInterfaceMock;
// public function setPrvekText($prvekText) :TestovaciEntityInterfaceMock;//    public function setPrvekInteger($prvekInteger) :TestovaciEntityInterfaceMock;//        public function setPrvekDate(\DateTime $prvekDate) :TestovaciEntityInterfaceMock;//        public function setPrvekDatetime(\DateTime $prvekDatetime) :TestovaciEntityInterfaceMock;//        public function setPrvekTimestamp(\DateTime $prvekTimestamp) :TestovaciEntityInterfaceMock;//        public function setPrvekBoolean($prvekBoolean) :TestovaciEntityInterfaceMock;        
}
class TestovaciEntityMock extends EntityAbstract implements TestovaciEntityInterfaceMock {      
    //V EntityAbstract JE Identity
        /**
         *
         * @var string
         */   
        private $celeJmeno;    
        private $prvekChar;
        private $prvekVarchar;    
        private $jmenoClovek;
        private $prijmeniClovek;
        //private $prvekText;        private $prvekInteger;            private $prvekBoolean;   private $prvekDate;        private $prvekDatetime;           private $prvekTimestamp;
    //----------------------------------------------------- 
        public function getCeleJmeno() : string {
            return $this->celeJmeno;
        }
        public function getPrvekVarchar() : string {
            return $this->prvekVarchar;
        }
        public function getPrvekChar(): string {
            return $this->prvekChar;
        }        
        public function getJmenoClovek(): string {
            return $this->jmenoClovek;
        }        
        public function getPrijmeniClovek(): string {
            return $this->prijmeniClovek;
        }        
        //-----------------------------------
        public function setCeleJmeno( string $celeJmeno) : TestovaciEntityInterfaceMock {
           $this->celeJmeno = $celeJmeno;
           return $this;
        }
        public function setPrvekVarchar($prvekVarchar) :TestovaciEntityInterfaceMock {
            $this->prvekVarchar = $prvekVarchar;       
            return $this;        
        }
        public function setPrvekChar($prvekChar) :TestovaciEntityInterfaceMock {
            $this->prvekChar = $prvekChar;
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
}


class RowObjectMock extends RowObjectAbstract implements RowObjectInterface {
    public $celeJmeno;    
    public $prvekChar;
    public $prvekVarchar;    
    public $jmenoClovek;
    public $prijmeniClovek;
    
}


class MethodNameHydratorMock implements MethodNameHydratorInterface {    
    public function hydrate(string $name): string {
        return 'set' . ucfirst($name);
    }        
    public function extract(string $name): string {       
        return 'get' . ucfirst($name);
    }    
}
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





class CeleJmenoEntityHydratorMock implements EntityHydratorInterface {
    public function hydrate( EntityInterface $entity, RowObjectInterface $rowObject): void {
        
    }
    public function extract( EntityInterface $entity, RowObjectInterface $rowObject ): void {
        
    }
}



interface TestovaciEntityRepositoryInterfaceMock {    
    /**
     * 
     * @param IdentityInterface $identity
     * @return TestovaciEntityInterfaceMock|null
     */
    public function get ( IdentityInterface $identity ): ?TestovaciEntityInterfaceMock;    
    /**
     * 
     * @param TestovaciEntityInterfaceMock $entity
     * @return void
     */
    public function add( TestovaciEntityInterfaceMock $entity ): void;        
    /**
     * 
     * @param TestovaciEntityInterfaceMock $entity 
     * @return void
     */
    public function remove( TestovaciEntityInterfaceMock $entity  ): void;
}




class TestovaciEntityRepositoryMock extends RepositoryAbstract implements TestovaciEntityRepositoryInterfaceMock {                         
    // private $identityHydrator;         
    
    public function __construct(            
                                TestovaciDaoInterfaceMock $testovaciDao,  RowDataInterface $rowData /*docasne*/,
//                                
                               // RowObjectHydratorInterface $rowObjectHydrator,                                  
                                EntityHydratorInterface $oneToOneEHydrator
                                //EntityHydratorInterface $celeJmenoEHydrator
                                
                                 //IdentityHydratorInterface $identityHydrator  
                               ) {                 
        $this->dao = $testovaciDao;     //->dao  -  definovanno v abstractu repository  
                $this->rowData = $rowData;
                  
        //$this->registerRowObjectHydrator( $rowObjectHydrator); //v abstractu     
        //$this->registerEntityHydrator($celeJmenoEHydrator); 
        $this->registerEntityHydrator($oneToOneEHydrator);         
        
//        $this->identityHydrator = $identityHydrator;               
    }                     
    public function get ( IdentityInterface $identity ): ?TestovaciEntityInterfaceMock {
        $index = $this->indexFromIdentity($identity);
        if (!isset($this->collection[$index])) {
            //$rowData = new RowData(); 
            //$rowData = $this->dao->get( $identity->getKeyHash() ); // vraci konstantni pole - hodnoty z úložistě, $keyHash  zatim neni v metode get pouzito
                        
            $this->recreateEntity( $identity /* , $rowData)*/ ); // v abstractu,    zarazeni do collection z uloziste, pod indexem  $index
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
            
    public function add( TestovaciEntityInterfaceMock $entity ): void {                
//        $index = $this->indexFromEntity($paper);
//        $this->addEntity($paper, $index);
    }
    
    public function remove( TestovaciEntityInterfaceMock $entity  ): void {                
//        $index = $this->indexFromEntity($paper);
//        $this->removeEntity($entity, $index);
    }
    
    
    //------------------------------------------------
    protected function createRowObj() : RowObjectInterface{
        return new RowObjectMock();  
    }
    
    protected function createEntity( $identity): TestovaciEntityInterfaceMock {
        return new TestovaciEntityMock( $identity /*.....tady ma byt identita*/);
    }



    protected function indexFromRow($row) /*keyHashFromRow*/ {
        return $row['id'];
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
    private $identityHydrator;    
    
    private $testovaciDaoM;
    
    private $testovaciRowDataM;
    
    private $poleJmen; 
    private $oneToOneEHydrator ;

            
//--------------------------------------------------------------------------------    
    public function setUp(): void {
       
        
        $this->testovaciDaoM = new TestovaciDaoMock();        
        //$rowObjectHydrator = new $rowObjectHydratorMock();
        
                    $this->testovaciRowDataM = new RowData( ['Klic1' => 'aaa', 'Klic2' => 'B' , 
                                                             'prvekVarchar' ,  'prvekChar' , 'jmenoClovek', 'prijmeniClovek' ] );   
        
        
        $this->poleJmen =  [  "prvekVarchar" ,  "prvekChar" , 'jmenoClovek', 'prijmeniClovek'  ] ;   
        $this->oneToOneEHydrator = new OneToOneEntityHydrator( new MethodNameHydratorMock(),
                                                               new OneToOneFilterMock( $this->poleJmen) );       
        $this->celeJmenoEHydrator = new CeleJmenoEntityHydratorMock();
        //$this->identityHydrator = new IdentityHydrator();                                   
    }        
        
        
    public function testGet() {       
        $testovaciKeyHash   = [ 'Klic1' => 'aaa', 'Klic2' => 'B'  ];   // [ 'Klic1' => 'aa', 'Klic2' => 'bb'  ];
        $testovaciAttribute = [ 'Klic1' ,'Klic2'  ];                   // [ 'Klic1' ,'Klic2'  ];              
        $testovaciIdentityM = new Identity ( $testovaciAttribute );     //pozn. neni generovany klic v entite   
        $testovaciIdentityM->setKey( $testovaciKeyHash );
        //------------------
        $testovaciRepository = new TestovaciEntityRepositoryMock( 
                                $this->testovaciDaoM,   $this->testovaciRowDataM,         
                                //new RowObjectHydratorMock(), // RowObjectHydratorInterface $rowObjectHydratorMock,                                
                                $this->oneToOneEHydrator,
                                //$this->celeJmenoEHydrator 
                                //  $this->identityHydrator
        );        
        /* get */ 
        $testovaciEntityNovy = $testovaciRepository->get( $testovaciIdentityM ); //vraceno  nove vyrobene         
        $this->assertInstanceOf( TestovaciEntityInterfaceMock::class, $testovaciEntityNovy );        /*  ocekavana,*/  /*aktualni (nejak vyrobena) */        
        //$this->assertEquals( $this->testovaciIdentityM, $testovaciEntityNovy->getIdentity());   zbytecne    
        $this->assertTrue( $testovaciEntityNovy->isPersisted() );
                
        /* get  */
        $testovaciEntityStavajici = $testovaciRepository->get( $testovaciIdentityM ); //vraceno  z repozitory (stavajici)        
        $this->assertInstanceOf( TestovaciEntityInterfaceMock::class, $testovaciEntityStavajici );    /*  ocekavana,*/  /*aktualni (nejak vyrobena) */        
        //$this->assertEquals( $this->testovaciIdentityM, $testovaciEntityStavajici->getIdentity());   zbytecne   
        $this->assertTrue( $testovaciEntityStavajici->isPersisted() );
                
        $this->assertEquals( $testovaciEntityNovy, $testovaciEntityStavajici );  
                
        //chceme testovat 'identickou totoznost objektu'  v  obou obdrzenych promennych - 1 instance objektu, dve instance odkazu na objekt ( 2 promenné )
        $this->assertTrue($testovaciEntityNovy == $testovaciEntityStavajici)  ;  //ok
        $this->assertTrue($testovaciEntityNovy === $testovaciEntityStavajici)  ; //ok
        //jde o stale týž identicky objekt, zde pristupny ze dvou promennych
        $testovaciEntityNovy->setPrvekChar('y');            //nastavuji stále v jednom objektu
        $testovaciEntityStavajici->setPrvekVarchar('YYY');
        $this->assertTrue($testovaciEntityNovy == $testovaciEntityStavajici)  ;  //ok
        $this->assertTrue($testovaciEntityNovy === $testovaciEntityStavajici)  ; //ok                  
        
         
         
        //* -------------------obracene poradi v zápisu prvků  klice keyhash  */
        $testovaciKeyHashObraceny   = [  'Klic2' => 'B', 'Klic1' => 'aaa'  ];   //  v opacnem poradi
        $testovaciAttributeObraceny = [  'Klic2', 'Klic1'   ];                         
        $testovaciIdentityM = new Identity ( $testovaciAttributeObraceny  );     //pozn. neni generovany klic v entite   
        $testovaciIdentityM->setKey(  $testovaciKeyHashObraceny  );
         
        /* get  */
        $testovaciEntityStavajici = $testovaciRepository->get( $testovaciIdentityM ); //vraceno  z repozitory (má být stavajici)   
        $this->assertTrue( $testovaciEntityStavajici->isPersisted() );
        $this->assertInstanceOf( TestovaciEntityInterfaceMock::class, $testovaciEntityStavajici );    /*  ocekavana,*/  /*aktualni (nejak vyrobena) */        
        
        //$this->assertEquals( $testovaciIdentityM, $testovaciEntityStavajici->getIdentity());          
        //$this->assertTrue ( $testovaciIdentityM === $testovaciEntityStavajici->getIdentity() ) ;
        
        $this->assertTrue( $testovaciEntityNovy == $testovaciEntityStavajici, '***porovnani bezne - neni equal objekt***');  //--porovnani 'bezne' , 
        // identity maji pořadí zápisu prvků  pole keyhashe  prohozené
        // ->neni equal 
  
        $this->assertTrue( $testovaciEntityNovy === $testovaciEntityStavajici, '***není ten samý (identický) objekt***');
        // chceme dostat stejny objekt,  identické
        
                
//        $this->assertEquals( $testovaciEntityNovy, $testovaciEntityStavajici );   
//        $this->assertTrue($testovaciEntityNovy == $testovaciEntityStavajici)  ;  //ok
//        $this->assertTrue($testovaciEntityNovy === $testovaciEntityStavajici)  ; //ok         
         
         //----------------------------------------------------------------------------------------------
         //priklad se dvema ruznymi instancemi objektu
         //ekvivalentni
         $a = new TestovaciEntityMock( $testovaciIdentityM );
         $b = new TestovaciEntityMock( $testovaciIdentityM );        
         $this->assertEquals($a, $b);           
         $this->assertTrue($a == $b)  ;
         
         //neekvivalentni
         $a->setPrvekChar('AAA');         
         $b->setPrvekChar('BBB');
         $this->assertNotEquals($a, $b);           
         $this->assertFalse($a == $b)  ;         
         
         //neidenticke
         $a = new TestovaciEntityMock( $testovaciIdentityM );
         $a->setPrvekChar('AAA');
         $b = new TestovaciEntityMock( $testovaciIdentityM );
         $b->setPrvekChar('AAA');
         $this->assertEquals($a, $b);           
         $this->assertTrue($a == $b);
         $this->assertNotTrue($a === $b);
         
                           
        //$this->poleJmen =  [  "prvekVarchar" ,  "prvekChar" , 'jmenoClovek', 'prijmeniClovek'  ] 
        //$this->assertObjectHasAttribute($attributeName, $testovaciEntityNovy);  // nevhodne (nesmyslné) je testovat atributy, protože jsou PRIVÁTNÍ
                        
//        // toto je test, ale na identitu         
//        $this->testovaciKeyHash   = [ 'Klic1' => 'aaa', 'Klic2' => 'B'  ];   // [ 'Klic1' => 'aa', 'Klic2' => 'bb'  ];
//        $this->testovaciAttribute = [ 'Klic1' ,'Klic1'  ];                   // [ 'Klic1' ,'Klic2'  ];              
//        $this->testovaciIdentityM = new Identity ( $this->testovaciAttribute );     //pozn. neni generovany klic v entite   
//        $this->expectException( MismatchedIndexesToKeyAttributeFieldsException::class );
//        $this->testovaciIdentityM->setKeyHash( $this->testovaciKeyHash );
        
        
        
        //----------------------------------------------------
        //---------------------------------------------------- ma  byt moznost identity s klici 'prazdnymi'   ???
        $testovaciKeyHash   = [  ];  
        $testovaciAttribute = [  ];  
        $testovaciIdentityM = new Identity ( $testovaciAttribute );     //pozn. neni generovany klic v entite   
        $testovaciIdentityM->setKey( $testovaciKeyHash );
        
        /* get  */
        $testovaciEntityNovy = $testovaciRepository->get( $testovaciIdentityM ); //vraceno  nove vyrobene         
        $this->assertInstanceOf( TestovaciEntityInterfaceMock::class, $testovaciEntityNovy );           /*  ocekavana,*/  /*aktualni (nejak vyrobena) */        
        //$this->assertEquals( $this->testovaciIdentityM, $testovaciEntityNovy->getIdentity());    zbytecne    
        $this->assertTrue( $testovaciEntityNovy->isPersisted() );
                
        /* get  */
        $testovaciEntityStavajici = $testovaciRepository->get( $testovaciIdentityM ); //vraceno  z repozitory (stavajici)        
        $this->assertInstanceOf( TestovaciEntityInterfaceMock::class, $testovaciEntityStavajici );       /*  ocekavana,*/  /*aktualni (nejak vyrobena) */        
        //$this->assertEquals( $this->testovaciIdentityM, $testovaciEntityStavajici->getIdentity());   zbytecne   
        $this->assertTrue( $testovaciEntityStavajici->isPersisted() );
                
        $this->assertEquals( $testovaciEntityNovy, $testovaciEntityStavajici );  
        
        $this->assertTrue($testovaciEntityNovy == $testovaciEntityStavajici)  ;  //ok
        $this->assertTrue($testovaciEntityNovy === $testovaciEntityStavajici)  ; //ok
  
        
        
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
