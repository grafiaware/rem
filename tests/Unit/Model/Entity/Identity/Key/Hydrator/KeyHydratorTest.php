<?php
namespace Test\KeyHydratorTest;

use PHPUnit\Framework\TestCase;
//
//use Model\Entity\Identity\Hydrator\IdentityHydrator;
//use Model\Entity\Identity\Hydrator\Exception\MissingAttributeFieldValueException;
//use Model\Entity\Identity\Hydrator\Exception\MissingPropertyRowObjectException;
//
//use Model\Entity\Identity\IdentityInterface;
//use Model\Entity\Identity\Exception\MismatchedIndexesToKeyAttributeFieldsException;
//use Model\Entity\Identity\Exception\AttemptToSetGeneratedKeyException;
//
//use Model\Entity\Identity\Hydrator\NameHydrator\AttributeNameHydratorInterface;
//use Model\RowObject\RowObjectInterface;
//use Model\RowObject\RowObjectAbstract;






//----------------------------------------------------------------------------------------------------
/**
 * Description of IdentityHydratorTest
 *
 * @author vlse2610
 */
class KeyHydratorTest extends TestCase {
//    /**
//     * @var AttributeNameHydratorInterface 
//     */    
//    private $nameHydrator;       
//    
    private $testovaciHash;    
    private $testovaciAttribute;
//    
//    /**
//     *
//     * @var IdentityInterface 
//     */
//    private $testovaciIdentity;    
//    /**
//     * @var RowObjectInterface
//     */
   private $testovaciRowObject; 
    
   
   
    
    
    public function setUp(): void {       
  
    }
        
    
    
    
    public function testHydrate_KlicNeniGenerovany_SNameHydratorem() {    
        $this->nameHydrator = new IdentityNameHydratorMock_ForIdentityHydratorTest(); 
        
        $this->testovaciRowObject = new TestovaciRowObjectMock();
        $this->testovaciRowObject->klic1 = 'aa';
        $this->testovaciRowObject->klic2 = 'bb';       
        
        $this->testovacHash   = [ 'klic1|' => 'aa', 'klic2|' => 'bb'  ];     //ocekavany  v  identite         
        $this->testovaciAttribute = [ 'klic1|' , 'klic2|' ];        
        
        $this->testovaciKey = new IdentityMock_ForIdentityHydratorTest( $this->testovaciAttribute ); // klic neni generovany          
        $identityHydrator = new IdentityHydrator( $this->nameHydrator );        
        $identityHydrator->hydrate( $this->testovaciIdentity, $this->testovaciRowObject );                
        $this->assertEquals( $this->testovaciKeyHash , $this->testovaciIdentity->getKeyHash() ) ;      
        //print_r ($this->testovaciIdentity);  
        
        //opakovany hydrate
        $this->testovaciKeyHash   = [ 'klic1|' => 'aa', 'klic2|' => 'bbbb'  ]; //ocekavany      
        $this->testovaciRowObject->klic2 = 'bbbb';
        $identityHydrator->hydrate( $this->testovaciIdentity, $this->testovaciRowObject );                
        $this->assertEquals( $this->testovaciKeyHash , $this->testovaciIdentity->getKeyHash() ) ;  
       
        //kdyz prebyva v row objektu vlastnost - nevadi 
        $this->testovaciRowObject->klic3 = 'cc';      
        $identityHydrator->hydrate( $this->testovaciIdentity, $this->testovaciRowObject );       
        $this->assertEquals( $this->testovaciKeyHash , $this->testovaciIdentity->getKeyHash() ) ;
        //print_r ($this->testovaciIdentity);
        
        //kdyz chybi v row objektu vlastnost - chyba
        $this->testovaciRowObject->klic2 = null;
        $this->expectException( MissingPropertyRowObjectException::class );
        $identityHydrator->hydrate( $this->testovaciIdentity, $this->testovaciRowObject );                         
    }         
    
   
    
    //------------------------------------------------------------------------------
    public function testHydrate_KlicNeniGenerovany_BezNameHydratoru() {           
        
        $this->testovaciRowObject = new TestovaciRowObjectMock_ForIdentityHydratorTest();
        $this->testovaciRowObject->klic1 = 'aa';
        $this->testovaciRowObject->klic2 = 'bb';       
        
        $this->testovaciKeyHash   = [ 'klic1' => 'aa', 'klic2' => 'bb'  ];     //ocekavany           
        $this->testovaciAttribute = [ 'klic1' ,'klic2'  ];        
        
        //bez NameHydratoru
        $this->testovaciIdentity = new IdentityMock_ForIdentityHydratorTest( $this->testovaciAttribute ); // klic neni generovany          
        $identityHydrator = new IdentityHydrator(  );        
        $identityHydrator->hydrate( $this->testovaciIdentity, $this->testovaciRowObject );                
        $this->assertEquals( $this->testovaciKeyHash , $this->testovaciIdentity->getKeyHash() ) ;       
        
        //kdyz prebyva v row objektu vlastnost - nevadi 
        $this->testovaciRowObject->klic3 = 'cc';      
        $identityHydrator->hydrate( $this->testovaciIdentity, $this->testovaciRowObject );       
        $this->assertEquals( $this->testovaciKeyHash , $this->testovaciIdentity->getKeyHash() ) ;
        //print_r ($this->testovaciIdentity);
        
        //kdyz chybi v row objektu vlastnost - chyba
        $this->testovaciRowObject->klic2 = null;
        $this->expectException( MissingPropertyRowObjectException::class );
        $identityHydrator->hydrate( $this->testovaciIdentity, $this->testovaciRowObject );      
    }
    
    public function testHydrate_KlicJeGenerovany_SNameHydratorem() {  
        $this->nameHydrator = new IdentityNameHydratorMock_ForIdentityHydratorTest(); 
        
        $this->testovaciRowObject = new TestovaciRowObjectMock_ForIdentityHydratorTest();
        $this->testovaciRowObject->klic1 = 'aa';
        $this->testovaciRowObject->klic2 = 'bb';    
        
        $this->testovaciKeyHash   = [ 'klic1|' => 'aa', 'klic2|' => 'bb'  ];     //ocekavany           
        $this->testovaciAttribute = [ 'klic1|' ,'klic2|'  ];        
        
        $this->testovaciIdentity = new IdentityMock_ForIdentityHydratorTest( $this->testovaciAttribute, true ); // klic je generovany  
        $identityHydrator = new IdentityHydrator( $this->nameHydrator );        
        $identityHydrator->hydrate( $this->testovaciIdentity, $this->testovaciRowObject );        
        $this->assertEquals( $this->testovaciKeyHash , $this->testovaciIdentity->getKeyHash() ) ;  
        //print_r ($this->testovaciIdentity);  
        
        //kdyz prebyva v row objektu vlastnost - nevadi 
        $this->testovaciRowObject->klic3 = 'cc';      
        $identityHydrator->hydrate( $this->testovaciIdentity, $this->testovaciRowObject );       
        $this->assertEquals( $this->testovaciKeyHash , $this->testovaciIdentity->getKeyHash() ) ;
        //print_r ($this->testovaciIdentity);
        
        //kdyz chybi v row objektu vlastnost - chyba
        $this->testovaciRowObject->klic2 = null;
        $this->expectException( MissingPropertyRowObjectException::class );
        $identityHydrator->hydrate( $this->testovaciIdentity, $this->testovaciRowObject );      
    }    
        
    
    public function testHydrate_KlicJeGenerovany_BezNameHydratoru() {           
        //priprava rowobjektu
        $this->testovaciRowObject = new TestovaciRowObjectMock_ForIdentityHydratorTest();
        $this->testovaciRowObject->klic1 = 'aa';
        $this->testovaciRowObject->klic2 = 'bb';       
        
        $this->testovaciKeyHash   = [ 'klic1' => 'aa', 'klic2' => 'bb'  ];     //ocekavany           
        $this->testovaciAttribute = [ 'klic1' ,'klic2'  ];        
        
        //bez NameHydratoru
        $this->testovaciIdentity = new IdentityMock_ForIdentityHydratorTest( $this->testovaciAttribute, true ); // klic je generovany  
        $identityHydrator = new IdentityHydrator(  );        
        $identityHydrator->hydrate( $this->testovaciIdentity, $this->testovaciRowObject );        
        $this->assertEquals( $this->testovaciKeyHash , $this->testovaciIdentity->getKeyHash() ) ;  
       //print_r ($this->testovaciIdentity);  
        
        //-----  prebyva v row objektu vlastnost - nevadi ----------------------------------
        $this->testovaciRowObject->klic3 = 'cc';      
        $identityHydrator->hydrate( $this->testovaciIdentity, $this->testovaciRowObject );       
        $this->assertEquals( $this->testovaciKeyHash , $this->testovaciIdentity->getKeyHash() ) ;
        //print_r ($this->testovaciIdentity);
        
        //--------------- chybi v row objektu vlastnost - chyba --------------------------
        $this->testovaciRowObject->klic2 = null;
        $this->expectException( MissingPropertyRowObjectException::class );
        $identityHydrator->hydrate( $this->testovaciIdentity, $this->testovaciRowObject );      
    }
    
    
    
//***********************************************************************************************   
//*** exrtract **********************************************************************************   
    
    
    
    public function testExtract_KlicNeniGenerovany_SNameHydratorem() {    
        $this->nameHydrator = new IdentityNameHydratorMock_ForIdentityHydratorTest(); 
        
        $this->testovaciRowObject = new TestovaciRowObjectMock_ForIdentityHydratorTest(); //ocekavany vysleddek
        $this->testovaciRowObject->klic1 = 'aa';
        $this->testovaciRowObject->klic2 = 'bb';       
        
        $this->testovaciKeyHash   = [ 'klic1|' => 'aa', 'klic2|' => 'bb'  ];             
        $this->testovaciAttribute = [ 'klic1|' , 'klic2|' ];         
        
        $novyRowObject = new TestovaciRowObjectMock_ForIdentityHydratorTest();
        //priprava identity
        $this->testovaciIdentity = new IdentityMock_ForIdentityHydratorTest( $this->testovaciAttribute ); // klic neni generovany 
        $this->testovaciIdentity->setKey( $this->testovaciKeyHash );
        
        $identityHydrator = new IdentityHydrator( $this->nameHydrator );        
        $identityHydrator->extract( $this->testovaciIdentity,$novyRowObject );         
        
        foreach ($this->testovaciKeyHash as $key=>$value) {
            $this->assertEquals ($this->testovaciRowObject, $novyRowObject );
        }
        
        print_r ($novyRowObject);  
    }
    
    
    public function testExtract_KlicNeniGenerovany_SNameHydratorem_MissingAttributeFieldValueException() {
        $this->nameHydrator = new IdentityNameHydratorMock_ForIdentityHydratorTest(); 
        
        //---------------------------- chybí hodnota pole klíče   ---------------------
        $this->testovaciKeyHash   = [ 'klic1|' => 'aa' , 'klic2|' => NULL  ];   //chybí hodnota pole klíče     
        $this->testovaciAttribute = [ 'klic1|' , 'klic2|' ];       
        
        $this->testovaciIdentity = new IdentityMock_ForIdentityHydratorTest( $this->testovaciAttribute ); // klic neni generovany 
        $this->testovaciIdentity->setKey( $this->testovaciKeyHash );                
        
        $novyRowObject = new TestovaciRowObjectMock_ForIdentityHydratorTest();        
        
        $identityHydrator = new IdentityHydrator( $this->nameHydrator );   
        $this->expectException( MissingAttributeFieldValueException::class  );
        $identityHydrator->extract( $this->testovaciIdentity,$novyRowObject );                 
        
        print_r ($novyRowObject);          
    }     
        
    public function testExtract_KlicNeniGenerovany_MismatchedIndexesToKeyAttributeFieldsException()  {
        //---------------------------- prebyva hodnota pole klíče   ---------------------
        $this->testovaciKeyHash   = [ 'klic1|' => 'aa' , 'klic2|' => 'bb' , 'klic3|' => 'cc'];       
        $this->testovaciAttribute = [ 'klic1|' , 'klic2|' ];                     
        $this->testovaciIdentity = new IdentityMock_ForIdentityHydratorTest( $this->testovaciAttribute ); // klic neni generovany     
        //vyhodi vyjimku jiz pri nastaveni identity
        $this->expectException(MismatchedIndexesToKeyAttributeFieldsException::class );        
        $this->testovaciIdentity->setKey( $this->testovaciKeyHash );
    }
    
    
     public function testExtract_KlicJeGenerovany_AttemptToSetGeneratedKeyException() {    
        $this->testovaciKeyHash   = [ 'klic1|' => 'aa', 'klic2|' => 'bb'  ];             
        $this->testovaciAttribute = [ 'klic1|' , 'klic2|' ];        
        
        $novyRowObject = new TestovaciRowObjectMock_ForIdentityHydratorTest();
        
        $this->testovaciIdentity = new IdentityMock_ForIdentityHydratorTest( $this->testovaciAttribute, true); // klic je generovany  
        //vyhodi vyjimku jiz pri nastaveni identity
        $this->expectException(AttemptToSetGeneratedKeyException::class );        
        $this->testovaciIdentity->setKey( $this->testovaciKeyHash );        
    }    
 
}

//
//class IdentityMock_ForIdentityHydratorTest /*implements IdentityInterface */{
//    /**
//     *
//     * @var bool Klíč je generovaný.
//     */
//    private $isGeneratedKey;       
//    /**
//     * Pole, které jako hodnoty má názvy(jména) polí částí klíče v asoc. poli keyHash.
//     * @param array $attribute
//     */
//    private $attribute;    
//    /**
//     * Klíč - asoc.pole dvojic (KeyValue pair) jmeno(casti klice)->hodnota(casti klice).
//     * @var array 
//     */
//    private $keyHash;
//      
//    public function __construct ( array $attribute, $isGeneratedKey=FALSE ) /*: IdentityInterface*/ {
//        $this->isGeneratedKey = (bool) $isGeneratedKey;
//        $this->attribute = $attribute;
////        $this->id = spl_object_hash($this);
////        $this->idMD5 = md5($this->id);
//    }    
//    public function isGenerated() : bool {
//        return $this->isGeneratedKey;
//    }       
//    public function getKeyAttribute() {
//        return $this->attribute;
//    }
//    public function getKeyHash() {
//        return $this->keyHash;
//    }         
//    public function setKey( array $keyHash ) {
//        if ($this->isGeneratedKey) {
//            throw new  AttemptToSetGeneratedKeyException('Klíč je generovaný a hodnoty generovaného klíče lze nastavit pouze hydrátorem při čtení z databáze.');           
//            //throw new \LogicException('Klíč je generovaný a hodnoty generovaného klíče lze nastavit pouze hydrátorem při čtení z databáze.');
//        }
//        if($this->attribute != array_keys($keyHash)) {
//            throw new MismatchedIndexesToKeyAttributeFieldsException('Jména částí klíče ($keyHash) neodpovídají polím atributu klíče zadaným v konstruktoru.');
//        }
//        $this->keyHash = $keyHash;
//        return $this;
//    }
//    public function isEqual( IdentityInterface /*Mock_ForIdentityHydratorTest*/ $identity ) : bool {
//        //$a == $b 	Equality 	TRUE if $a and $b have the same key/value pairs. - nezáleží na pořadí - testováno
//        //$a === $b 	Identity 	TRUE if $a and $b have the same key/value pairs in the same order and of the same types.)
//        return $this->keyHash == $identity->getKeyHash();
//    }        
//    public function hasEqualAttribute( IdentityInterface /*Mock_ForIdentityHydratorTest*/ $identity ) : bool {
//        return $this->attribute == $identity->getKeyAttribute();
//    }
//    
//}
//
//
//class IdentityNameHydratorMock_ForIdentityHydratorTest implements AttributeNameHydratorInterface {    
//    
//   public function hydrate(string $name): string {
//        return rtrim($name,'|');
//   }
//   
//    public function extract(string $name): string {
//        //return $name.'|';
//        return rtrim($name,'|');
//   }
//}
//
//
//class TestovaciRowObjectMock_ForIdentityHydratorTest extends RowObjectAbstract implements RowObjectInterface{  } 
//    
