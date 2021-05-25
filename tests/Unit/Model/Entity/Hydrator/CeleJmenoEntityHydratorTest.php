<?php
namespace Test\CeleJmenoEntityHydratorTest;


use PHPUnit\Framework\TestCase;

use Model\Entity\Hydrator\CeleJmenoEntityHydrator;


use Model\Entity\Identity\IdentityInterface;
use Model\Entity\Identity\Key\KeyInterface;

use Model\Entity\Hydrator\Filter\OneToManyFilterInterface;
use Model\Entity\Hydrator\NameHydrator\MethodNameHydratorInterface;
use Model\Entity\Hydrator\NameHydrator\AttributeNameHydratorInterface;
use Model\Entity\Hydrator\CeleJmenoGluerInterface;
use Model\Entity\EntityInterface;
use Model\Entity\EntityAbstract;
use Model\RowObject\RowObjectInterface;
use Model\RowObject\RowObjectAbstract;


class OneToManyFilterMock implements OneToManyFilterInterface {
     /**
     * @var array
     */
    private $poleJmen;                 
    public function __construct( array $poleJmen ) {
        $this->poleJmen = $poleJmen;        
    }        
    //Pozn. - getIterator vrací iterovatelný objekt.        
    public function getIterator() : \Traversable {        
         return new \ArrayIterator( $this->poleJmen );
    }             
}


class MethodNameHydratorMock implements MethodNameHydratorInterface {    
    public function hydrate(string $name): string {
        return 'set' . ucfirst($name);
    }        
    public function extract(string $name): string {       
        return 'get' . ucfirst($name);
    }    
}


class AttributeNameHydratorMock implements AttributeNameHydratorInterface {   
    public function hydrate( string $name ) : string {   
        return $name  ;        
    }  
    public function extract( string $name )  : string {                
       return  $name  ;                         
    }
}


    
class IdentityMock implements IdentityInterface {
    public function hasGeneratedKey() : bool {
        return false;
    }
    public function getKey(): KeyInterface{ 
        return $this->key;
    }    
    public function setKey( KeyInterface $key): void {
        $this->key = $key;
    }
 
}


interface EntityInterfaceMock extends EntityInterface {
        public function getCeleJmeno();       
        public function setCeleJmeno( string $celeJmeno) :TestovaciEntityMock;               
} 

class TestovaciEntityMock  extends EntityAbstract implements EntityInterfaceMock   {       
    private $celeJmeno;  
    private $celeJmenoDruhe;  
   
//------------------------------------------------------
     
    public function getCeleJmeno() : string {
        return $this->celeJmeno;
    }     
    public function getCeleJmenoDruhe() : string {
        return $this->celeJmenoDruhe;
    } 
              
    //-----------------------------------    
    public function setCeleJmeno( string $celeJmeno) :TestovaciEntityMock {
       $this->celeJmeno = $celeJmeno;
       return $this;
    }
     public function setCeleJmenoDruhe( string $celeJmeno) :TestovaciEntityMock {
       $this->celeJmenoDruhe = $celeJmeno;
       return $this;
    }  
}
    
interface RowObjectInterfaceMock extends RowObjectInterface{    
}

class RowObjectMock extends RowObjectAbstract implements RowObjectInterface {              
    public $uidPrimarniKlicZnaky ;         

    public $titulPred;
    public $jmeno;
    public $prijmeni;
    public $titulZa; 
    
    //jen pro extract
    public $jmeno2;
    public $prijmeni2;
}


/**
 * Spojuje a rozpojuje celé jméno. Zatím s oddělovačem '|'.
 */
class CeleJmenoGluerMock implements CeleJmenoGluerInterface {
    /**
     * @param array $castiJmena     asoc pole,  jmeno vlastnosti rowObjectu => hodnota vlastnosti rowObjectu  
     * @param array $listJmen       seznam casti jmena, urcuje tez poradi casti ( array ['jmeno', 'prijmeni' ] ) 
     * @return string               spojene celé jmeno
     */
    public function stick(  array $castiJmena,  array $listJmen) : string {                
        $castiJmenaSerazenePodleFiltru = [];
        foreach ( $listJmen as  $v) {            
            $castiJmenaSerazenePodleFiltru[] = $castiJmena[$v];            
        }            
        $celeJmeno = implode("|", $castiJmenaSerazenePodleFiltru);
        return $celeJmeno;       
    }
    
    /**
     * 
     * @param string $celeJmeno vstupujici retezec ( spojene části jména s oddelovacem '|')
     * @param array $listJmen   seznam casti jmena, v ocekavanem poradi ( např.array ['jmeno', 'prijmeni' ] )
     * @return array            asoc. pole casti jmen, tj. jmeno casti => hodnota casti
     */
    public function unstick( string $celeJmeno, array $listJmen ) : array {        
        //array : jmeno vlastn.rowObjectu => data vlastn.rowObjectu                
        $casti = [];
        $castiJmena = explode("|", $celeJmeno);          
        $i=0;
        foreach ( $listJmen as $value ) {            
            $casti[$value] = $castiJmena[$i]; 
            $i = $i+1;
        }                        
        return $casti;
    } 
}


//###################################################################################
/**
 * Description of CeleJmenoEntityHydratorTest
 *
 * @author vlse2610
 */
class CeleJmenoEntityHydratorTest extends TestCase {     
    private $poleJmen  ;
    
    public function setUp(): void {     
         // 1 -  nastaveni "konstant"               
    }        
    
    public function testCeleJmenoEntityHydrate() : void {
        $this->poleJmen = [
//            "celeJmeno" =>  ["jmeno" , "prijmeni"],
//            "celeJmenoDruhe" => ["jmeno2" , "prijmeni2"]
              "celeJmeno" =>  ["prijmeni", "jmeno" ],
              "celeJmenoDruhe" => ["prijmeni2", "jmeno2"]
            ];
        // 2 - zdrojovy datovy objekt testovaci
        $testovaciZdrojovyRowObjectNaplneny = new RowObjectMock();                    
        $testovaciZdrojovyRowObjectNaplneny->jmeno          = "BARNABÁŠ";
        $testovaciZdrojovyRowObjectNaplneny->prijmeni       = "KOSTKA"; 
        $testovaciZdrojovyRowObjectNaplneny->titulPred      = "MUDrC.";
        $testovaciZdrojovyRowObjectNaplneny->titulZa        = "vezír";       

        $testovaciZdrojovyRowObjectNaplneny->jmeno2          = "BARNABÁŠ2";
        $testovaciZdrojovyRowObjectNaplneny->prijmeni2       = "KOSTKA2"; 
       
        // 3 - filtr, nastaveni filtru, hydrator (filtr do hydratoru)                               
        $celeJmenoEntityHydrator = new CeleJmenoEntityHydrator( new AttributeNameHydratorMock(), new MethodNameHydratorMock(),
                                                                new OneToManyFilterMock( $this->poleJmen), 
                                                                new CeleJmenoGluerMock () ) ;
        
        // 4 -  hydratovani  ############################################
        $identity = new IdentityMock(  );
        $novaPlnenaTestovaciEntity  =  new TestovaciEntityMock( $identity );           
        $celeJmenoEntityHydrator->hydrate( $novaPlnenaTestovaciEntity, $testovaciZdrojovyRowObjectNaplneny );   
                
//  /* */  print_r ("\n" ."BLE****");
//        print_r ($novaPlnenaTestovaciEntity);
//        echo ($novaPlnenaTestovaciEntity->getCeleJmeno());
//        echo ($novaPlnenaTestovaciEntity->getCeleJmenoDruhe());
        
        
        // 5 - kontrola hydratace           
        $oneToManyFilterMock = new OneToManyFilterMock( $this->poleJmen );
        foreach (  $oneToManyFilterMock->getIterator() as  $key => $value  ) { /* $key je pro vytvoreni metody entity, $value je pole  vlastnosti rowobjectu!!!!!*/      
            $i = 0;
            $listArray = [];
            foreach ($value as $item) {              
                $listArray [$i] =  $testovaciZdrojovyRowObjectNaplneny->$item;
                $i = $i + 1;                               
            }                               
            $methodNameHydrator = new MethodNameHydratorMock();
            $methodNameGet = $methodNameHydrator->extract( $key );
            //$getMethodName = "get" .  ucfirst( $key ); 
            //
            // assertEquals (ocekavana, aktualni hodnota v entite)                
            $this->assertEquals( implode("|", $listArray), $novaPlnenaTestovaciEntity->$methodNameGet(),
                                " *CHYBA* ");                  
        }   
         
//              "celeJmeno" =>  ["prijmeni", "jmeno" ],
//              "celeJmenoDruhe" => ["prijmeni2", "jmeno2"]
        $gluer = new CeleJmenoGluerMock();
        $this->assertEquals($gluer->stick(  [  "prijmeni" => $testovaciZdrojovyRowObjectNaplneny->prijmeni, 
                                               "jmeno" => $testovaciZdrojovyRowObjectNaplneny->jmeno ],  ["prijmeni", "jmeno" ]), 
                            $novaPlnenaTestovaciEntity->getCeleJmeno(), 
                            " *CHYBA*při hydrataci ");
       
        $this->assertEquals($gluer->stick( [ "prijmeni2" => $testovaciZdrojovyRowObjectNaplneny->prijmeni2 , 
                                              "jmeno2" => $testovaciZdrojovyRowObjectNaplneny->jmeno2  ], ["prijmeni2", "jmeno2" ] ),               
                            $novaPlnenaTestovaciEntity->getCeleJmenoDruhe(), 
                            " *CHYBA*při hydrataci ");       
                
    }
    
    
    
    
    public function testCeleJmenoEntityExtract() : void {
        $this->poleJmen = [           
            "celeJmeno" =>       [ "prijmeni", "jmeno"],     //pro    filter ,  pozadovane poradi 
            "celeJmenoDruhe" =>  [ "prijmeni2", "jmeno2"]
            ];
        
        // 2 - zdrojovy datovy objekt testovaci         
        
        $testovaciZdrojovaEntityNaplnena = new TestovaciEntityMock ( new IdentityMock(   ) );              
        //$testovaciZdrojovaEntityNaplnena->setCeleJmeno(      "BARNABÁŠ|" . "KOSTKA" ); 
        //$testovaciZdrojovaEntityNaplnena->setCeleJmenoDruhe(      "BARNABÁŠ2|" . "KOSTKA2" ); 
        
        $testovaciZdrojovaEntityNaplnena->setCeleJmeno(      "KOSTKA|BARNABÁŠ" );
        $testovaciZdrojovaEntityNaplnena->setCeleJmenoDruhe( "KOSTKA2|BARNABÁŠ2"  ); 
        
                                  
        // 3 - filtr, nastaveni filtru, hydrator (filtr do hydratoru)                                               
        $oneToOneEntityHydrator = new CeleJmenoEntityHydrator( new AttributeNameHydratorMock(), new MethodNameHydratorMock(),
                                                               new OneToManyFilterMock( $this->poleJmen ),
                                                               new CeleJmenoGluerMock () ) ;
        
        // 4 -  extrakce  ##########################################
        $novyPlnenyRowObject  =  new RowObjectMock ();           
        $oneToOneEntityHydrator->extract( $testovaciZdrojovaEntityNaplnena, $novyPlnenyRowObject );          
         
//  /* */ print_r ($testovaciZdrojovaEntityNaplnena); 
//        print_r ($novyPlnenyRowObject);
 
        
        // 5 - kontrola extrakce  ( ocekavany: Entita,  kontrolovany: rowObject)
        //pozn. foreach jde podle "vlastniho" poradi
        $oneToManyFilterMock = new OneToManyFilterMock( $this->poleJmen );
        foreach (  $oneToManyFilterMock->getIterator()  as  $key => $value ) { /* $key je pro vytvoreni metody entity, $value  je pole se jmeny vlastnosti rowobjectu!!!!!*/                  
            $i = 0;
            $listArray = [];
            foreach ($value as $item) {              
                $listArray [$i] =  $novyPlnenyRowObject->$item;
                $i = $i + 1;                               
            }                                 
            
            $methodNameHydrator = new MethodNameHydratorMock();
            $methodNameGet = $methodNameHydrator->extract( $key );
            //$getMethodName = "get" .  ucfirst( $key );        
            //
            // assertEquals (ocekavana, aktualni hodnota v entite)                
            $this->assertEquals( $testovaciZdrojovaEntityNaplnena->$methodNameGet(), implode("|", $listArray), /*z rowObjectu*/
                                 " *CHYBA* ");                  
        }   
        
//       "celeJmeno" =>  ["prijmeni", "jmeno" ],
//       "celeJmenoDruhe" => ["prijmeni2", "jmeno2"]    
        $gluer = new CeleJmenoGluerMock ();
        $this->assertEquals( $testovaciZdrojovaEntityNaplnena->getCeleJmeno(), 
                             $gluer->stick( [ "prijmeni" => $novyPlnenyRowObject->prijmeni, "jmeno" => $novyPlnenyRowObject->jmeno ], 
                                            ["prijmeni", "jmeno" ] ),               
                             " *CHYBA*při extrahování ");
        
        $this->assertEquals( $testovaciZdrojovaEntityNaplnena->getCeleJmenoDruhe(),
                             $gluer->stick( [ "prijmeni2" => $novyPlnenyRowObject->prijmeni2, "jmeno2" => $novyPlnenyRowObject->jmeno2 ],
                                            ["prijmeni2", "jmeno2" ] ),
                             " *CHYBA*ři extrahování ");
        
     
    }
        
    
    
}

