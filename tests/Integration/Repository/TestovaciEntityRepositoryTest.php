<?php
use PHPUnit\Framework\TestCase;

use Model\Repository\TestovaciEntityRepository;
use Model\Repository\TestovaciEntityRepositoryInterface;

use Model\Entity\EntityInterface;
use Model\Entity\EntityAbstract;
use Model\Entity\TestovaciEntityInterface;
use Model\Entity\Identity\Identity;
use Model\Entity\Identity\IdentityInterface;

use Model\Entity\Hydrator\OneToOneEntityHydrator;
use Model\Entity\Hydrator\Filter\OneToOneFilter;
use Model\Entity\Hydrator\CeleJmenoEntityHydrator;
use Model\Entity\Hydrator\Filter\OneToManyFilter;

use Model\Dao\TestovaciTableDaoInterface;

use Model\RowObject\RowObjectInterface;

//use Pes\Database\Metadata\MetadataProviderInterface;


/**
 * Description of TestovaciEntityRepositoryTest
 *
 * @author vlse2610
 */
class TestovaciEntityRepositoryTest  extends TestCase {
    /**
     *
     * @var RowObjectInterface 
     */
    private $testovaciRowObject;
    /**
     *
     * @var EntityInterface 
     */
    private $testovaciEntity;
    /**
     *
     * @var TestovaciEntityRepositoryInterface
     */
    private $repository;
            
    
    public function setUp(): void { 
        $this->testDateString = "2010-09-08";
        $this->testDate = DateTime::createFromFormat("Y-m-d", $this->testDateString)->setTime(0,0,0,0); 
        $this->testDateTimeString = "2005-06-07 22:23:24";
        $this->testDateTime = DateTime::createFromFormat("Y-m-d H:i:s", $this->testDateTimeString);       
        
        // 1 - naplneni testovaciho (zdrojoveho) row objektu        
        $this->testovaciRowObject = new  TestovaciRowObjectM();          
        
//        $this->testovaciZdrojovyRowObject->uidPrimarniKlicZnaky = "AAA*primarniKlic";        
//        $this->testovaciZdrojovyRowObject->jmeno          = "BARNABÁŠ";
//        $this->testovaciZdrojovyRowObject->prijmeni       = "KOSTKA"; 
//        $this->testovaciZdrojovyRowObject->titulPred      = "MUDrC.";
//        $this->testovaciZdrojovyRowObject->titulZa        = "vezír";               
//        $this->testovaciZdrojovyRowObject->prvekChar      = "*CHARY *testovaci**";
//        $this->testovaciZdrojovyRowObject->prvekVarchar   = "*VARCHARY-testovaci*";
//        $this->testovaciZdrojovyRowObject->prvekText      = "*TEXTY/ testovaci /*";
//        $this->testovaciZdrojovyRowObject->prvekInteger   = 666;   
//        $this->testovaciZdrojovyRowObject->prvekBoolean   = \TRUE;        
//        $this->testovaciZdrojovyRowObject->prvekDate      = $this->testDate;        // objekt    
//        $this->testovaciZdrojovyRowObject->prvekDatetime  = $this->testDateTime;    // objekt         
//        $this->testovaciZdrojovyRowObject->prvekTimestamp = $this->testDateTime;    // objekt                                   
//        foreach ($this->poleHodnot as $key => $value) {
//            $this->testovaciRowObject->$key = $value /*$this->poleHodnot[ $key ]*/;   }  
        
        // 1 - naplneni testovaci (zdrojove) entity   
        /** @var IdentityInterface  $identity */  
        $identity = new Identity();
        $this->testovaciEntity = new TestovaciEntityM ();   
        $this->testovaciEntity->setIdentity( $identity ) ;  
         
//        $this->testovaciZdrojovaEntity->setCeleJmeno(      "BARNABÁŠ" . "KOSTKA" );        
//        $this->testovaciZdrojovaEntity->setPrvekChar(      "*CHARY *testovaci* *" );
//        $this->testovaciZdrojovaEntity->setPrvekVarchar(   "*VARCHARY-testovaci-*" );
//        $this->testovaciZdrojovaEntity->setPrvekText(      "*TEXTY/ testovaci /*"); 
//        $this->testovaciZdrojovaEntity->setPrvekInteger(   666 );
//        $this->testovaciZdrojovaEntity->setPrvekBoolean(   \TRUE );
//        $this->testovaciZdrojovaEntity->setPrvekDate(      $this->testDate ); 
//        $this->testovaciZdrojovaEntity->setPrvekDatetime(  $this->testDateTime );                       
//        $this->testovaciZdrojovaEntity->setPrvekTimestamp( $this->testDateTime );
                
        $this->seznamJmenProOneToOne = [ "prvekVarchar" ,  "prvekChar" ,  "prvekText" , "prvekInteger" ,  "prvekBoolean" ,             
                        "prvekDate", "prvekDatetime", "prvekTimestamp" ];           
        $this->seznamJmenProCeleJmeno = [ "celeJmeno" => ["jmeno","prijmeni" ]   ]; 
                
        
        // 2 - vytvoření filtru/ů, hydrátor/ů, DaO objektu --- pro repository
        //--------------------------------------------------------------------------------------------------------
        $filterOneToOne = new OneToOneFilter( $this->seznamJmenProOneToOne );
        $hydratorOneToOne =  new OneToOneEntityHydrator( $filterOneToOne);        
        $filterOneToMany = new OneToManyFilter(  $this->seznamJmenProCeleJmeno );
        $hydratorCeleJmeno = new CeleJmenoEntityHydrator($filterOneToMany);
        
        $testovaciTableDao = new TestovaciDaoM();
        //---------------------------------------------------------------------                
        
        // 3 - tetstované repository 
        $this->repository =  new TestovaciEntityRepository( $testovaciTableDao, $hydratorOneToOne, $hydratorCeleJmeno );

    }
    //--------------------------------------------------
    
    
    public function testGetNull() : void { 
      
        $identity = new Identity();
        $this->assertNull($this->repository->get($identity),  "*CH* - není null" );                     
    }
       
    public function testAdd() : void {         
        $this->repository->add( $this->testovaciEntity  );
        
        $ctenaEntity = $this->repository->get( $this->testovaciEntity->getIdentity() );                
        $this->assertInstanceOf(TestovaciEntityM::class, $ctenaEntity, "*CH* - není instancí classy TestovaciEntityM ");  /*ocekavana, aktualni */
                
    }
    
    public function testGetEntity() : void {               
        // "existující identita";      
        $ctenaEntity = $this->repository->get($this->testovaciEntity->getIdentity());
        $this->assertEquals(  $this->testovaciEntity, $ctenaEntity, "*CH* - přečtená entity není equal uložené");                        
    }
   
    public function testRemove() : void {        
        $this->repository->remove($this->testovaciEntity->getIdentity() );
        
        $ctenaEntity = $this->repository->get($this->testovaciEntity->getIdentity());
        $this->assertTrue(is_null($ctenaEntity), "*CH* - remove nevymazal záznam o entity");     
    }
    
}
    //-------------------------------------------------------------------------------
    

class TestovaciDaoM  implements TestovaciTableDaoInterface  {
   
    public function get($uid): ?RowObjectInterface {
        $r = new TestovaciRowObjectM();
        return  $r
        ;
    }
    public function delete(RowObjectInterface $rowObject): void {
        ;
    }
    public function save(RowObjectInterface $rowObject): void {
        ;
    }
    public function find($sqlTemplateWhere, array $poleNahrad): array {
         $r = new TestovaciRowObjectM();
         $a = [ $r ];
         return  $a
        ;
    }
    
}




/**
 * Description of TestovaciRowObjectM
 *
 * @author vlse2610
 */
class TestovaciRowObjectM  implements RowObjectInterface{
             
    public $uidPrimarniKlicZnaky ;         

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
    


/**
 * Description of testovaciEntity_vModel
 *
 * @author vlse2610
 */
class TestovaciEntityM  extends EntityAbstract implements TestovaciEntityInterface {
   // private $uidPrimarniKlicZnaky ;  
    
    /**
     *
     * @var string
     */   
    private $celeJmeno;    
    
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
        
//------------------------------------------------------
         
     
    public function getCeleJmeno() : string {
        return $this->celeJmeno;
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
//    public function setUidPrimarniKlicZnaky ( $param ) {
//        $this->uidPrimarniKlicZnaky = $param;
//         return $this;
//    }
    
    public function setCeleJmeno( string $celeJmeno) :TestovaciEntityInterface {
       $this->celeJmeno = $celeJmeno;
       return $this;
    }

    public function setPrvekVarchar($prvekVarchar) :TestovaciEntityInterface {
        $this->prvekVarchar = $prvekVarchar;       
        return $this;        
    }

    public function setPrvekChar($prvekChar) :TestovaciEntityInterface {
        $this->prvekChar = $prvekChar;
        return $this;
        
    }

    public function setPrvekText($prvekText) :TestovaciEntityInterface {
        $this->prvekText = $prvekText;
        return $this;        
    }

    public function setPrvekInteger($prvekInteger) :TestovaciEntityInterface{
        $this->prvekInteger = $prvekInteger;
        return $this;       
    }

    public function setPrvekDate(\DateTime $prvekDate) :TestovaciEntityInterface{
        $this->prvekDate = $prvekDate;
        return $this;        
    }

    public function setPrvekDatetime(\DateTime $prvekDatetime) :TestovaciEntityInterface {
        $this->prvekDatetime = $prvekDatetime;
        return $this;        
    }

    public function setPrvekTimestamp(\DateTime $prvekTimestamp) :TestovaciEntityInterface {
        $this->prvekTimestamp = $prvekTimestamp;
        return $this;      
    }

    public function setPrvekBoolean($prvekBoolean) :TestovaciEntityInterface{
        $this->prvekBoolean = $prvekBoolean;
        return $this;
    }
}
    

