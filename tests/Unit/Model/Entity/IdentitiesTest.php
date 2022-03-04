<?php
namespace Test\IdentitiesTest;

use PHPUnit\Framework\TestCase;

use Model\Entity\Identities;
use Model\Entity\Identity\IdentityInterface;
use Model\Entity\Enum\IdentityTypeEnum;
use Model\Entity\Identity\IdentityAbstract;
use Model\Entity\Exception\MismatchedIdentitiesException;




interface KlicIdentityInterfaceMock extends IdentityInterface {    
}
interface RabbitIdentityInterfaceMock extends IdentityInterface {
}
interface HoleIdentityInterfaceMock extends IdentityInterface {
}

class RabbitIdentityNamesEnumMock extends IdentityTypeEnum{    
    const RABBITIDENTITYINTERFACEMOCK = RabbitIdentityInterfaceMock::class;
    const KLICIDENTITYINTERFACEMOCK = KlicIdentityInterfaceMock::class;
}
class RabbitIdentityMock extends IdentityAbstract implements RabbitIdentityInterfaceMock {   
    public function getTypeIdentity(): string {
        return RabbitIdentityNamesEnumMock::RABBITIDENTITYINTERFACEMOCK;
    }
}
class KlicIdentityMock extends IdentityAbstract implements KlicIdentityInterfaceMock {   
    public function getTypeIdentity(): string {
        return RabbitIdentityNamesEnumMock::KLICIDENTITYINTERFACEMOCK;
    }
}

class HoleIdentityNamesEnumMock extends IdentityTypeEnum{    
    const HOLEIDENTITYINTERFACEMOCK = HoleIdentityInterfaceMock::class;
}
class HoleIdentityMock extends IdentityAbstract implements HoleIdentityInterfaceMock {   
    public function getTypeIdentity(): string {
        return HoleIdentityNamesEnumMock::HOLEIDENTITYINTERFACEMOCK;
    }
}

/**
 * Description of IdentitiesTest
 *
 * @author vlse2610
 */
class  IdentitiesTest extends TestCase  {
    
      
    public function setUp(): void {  }
    
    
    public function testConstruct(  ) : void {             
        $this->assertInstanceOf( Identities::class, new Identities ( new RabbitIdentityNamesEnumMock() ) );                     
    }
    
    
    
    
    public function testAppend(  ) : void {    
        $rabbitIdentity =  new RabbitIdentityMock();       
        $klicIdentity =  new KlicIdentityMock();        
//        $arrayI [ RabbitIdentityNamesEnumMock::RABBITIDENTITYINTERFACEMOCK ] =  $rabbitIdentity;
//        $arrayI [ RabbitIdentityNamesEnumMock::KLICIDENTITYINTERFACEMOCK ] = $klicIdentity;        

        $identities =  new Identities ( new RabbitIdentityNamesEnumMock() );        
        $identities->append($rabbitIdentity);        
        $this->assertInstanceOf( Identities::class, $identities );                      
    }
    
    
     public function testAppend_MismatchedIdentitiesException_nejsou_vsechny(  ) : void {    
        $rabbitIdentity =  new RabbitIdentityMock();       
        $klicIdentity =  new KlicIdentityMock();        
//        $arrayI [ RabbitIdentityNamesEnumMock::RABBITIDENTITYINTERFACEMOCK ] =  $rabbitIdentity;
//        $arrayI [ RabbitIdentityNamesEnumMock::KLICIDENTITYINTERFACEMOCK ] = $klicIdentity;        

        $identities =  new Identities ( new RabbitIdentityNamesEnumMock() );        
        $identities->append($rabbitIdentity);        
       
        //chyba - pri pouziti Identities - v Identities nejsou vsechny identity
        $this->expectException( MismatchedIdentitiesException::class)  ; 
        $identities_1 = $identities->getIterator(); 
      
       
//        //pridat hole  ...chyba HoleIdentityMock není ve vyctovem typu, neni pripustna       
//        $holeIdentity =  new HoleIdentityMock();
//        $identities->append($holeIdentity);        
    }
    
    
    
    public function testAppend_MismatchedIdentitiesException_neni_pripustna(  ) : void {    
        $rabbitIdentity =  new RabbitIdentityMock();       
        $klicIdentity =  new KlicIdentityMock();        
//        $arrayI [ RabbitIdentityNamesEnumMock::RABBITIDENTITYINTERFACEMOCK ] =  $rabbitIdentity;
//        $arrayI [ RabbitIdentityNamesEnumMock::KLICIDENTITYINTERFACEMOCK ] = $klicIdentity;        

        $identities =  new Identities ( new RabbitIdentityNamesEnumMock() ); 
        $this->expectException( MismatchedIdentitiesException::class)  ;
        $identities->append($rabbitIdentity);             

        //pridat hole  ...chyba HoleIdentityMock není ve vyctovem typu, neni pripustna       
        $holeIdentity =  new HoleIdentityMock();        
        $identities->append($holeIdentity);                
    }
    
  
    
    
//   
//     /**
//     * V poli identit (druhý parametr constructoru Identities) chybí položky.
//     * @return void
//     */
//    public function testConstrucExceptiont_nejsouVsechny(  ) : void {             
//        $rabbitIdentity =  new RabbitIdentityMock();       
//        $klicIdentity =  new KlicIdentityMock();        
//        $arrayI [ RabbitIdentityNamesEnumMock::RABBITIDENTITYINTERFACEMOCK ] =  $rabbitIdentity;
//        //$arrayI [ RabbitIdentityNamesEnumMock::KLICIDENTITYINTERFACEMOCK ] = $klicIdentity;      
//      
//        $this->expectException( MismatchedIdentitiesException::class)  ;        
//        $identities =  new Identities ( new RabbitIdentityNamesEnumMock(), $arrayI  ) ;             
//    }
//       
//    //navic neberem
//    /**
//     * V poli identit (druhý parametr constructoru Identities) přebývají položky.
//     * @return void
//     */
//    public function testConstructException_navicNeberem(  ) : void {             
//        $rabbitIdentity =  new RabbitIdentityMock();       
//        $klicIdentity =  new KlicIdentityMock();        
//        $arrayI [ RabbitIdentityNamesEnumMock::RABBITIDENTITYINTERFACEMOCK ] =  $rabbitIdentity;
//        $arrayI [ RabbitIdentityNamesEnumMock::KLICIDENTITYINTERFACEMOCK ] = $klicIdentity;     
//        $arrayI ['aaa'] = $klicIdentity;
//        
//        $this->expectException( MismatchedIdentitiesException::class)  ;        
//        $this->assertInstanceOf( Identities::class,
//                                 new Identities ( new RabbitIdentityNamesEnumMock(), $arrayI  ) );             
//    }
//
//    
//    
//    public function testOffsetSet(  ) : void { 
//        $rabbitIdentity =  new RabbitIdentityMock();       
//        $klicIdentity =  new KlicIdentityMock();        
//        $arrayI [ RabbitIdentityNamesEnumMock::RABBITIDENTITYINTERFACEMOCK ] =  $rabbitIdentity;
//        $arrayI [ RabbitIdentityNamesEnumMock::KLICIDENTITYINTERFACEMOCK ] = $klicIdentity; 
//        $rabbitIdentities = new Identities ( new RabbitIdentityNamesEnumMock(), $arrayI  ) ;                   
//       
//        $rabbitIdentities->offsetSet( RabbitIdentityNamesEnumMock::KLICIDENTITYINTERFACEMOCK, $klicIdentity);
//        $this->assertTrue($rabbitIdentities->offsetExists( RabbitIdentityNamesEnumMock::KLICIDENTITYINTERFACEMOCK) );  
// 
//    }
// 
//    public function testOffsetSet_MismatchedIdentitiesException(  ) : void { 
//        $rabbitIdentity =  new RabbitIdentityMock();       
//        $klicIdentity =  new KlicIdentityMock();        
//        $arrayI [ RabbitIdentityNamesEnumMock::RABBITIDENTITYINTERFACEMOCK ] =  $rabbitIdentity;
//        $arrayI [ RabbitIdentityNamesEnumMock::KLICIDENTITYINTERFACEMOCK ] = $klicIdentity;        
//        $rabbitIdentities = new Identities ( new RabbitIdentityNamesEnumMock(), $arrayI  ) ;                   
// 
//        $this->expectException( MismatchedIdentitiesException::class)  ;        
//        $rabbitIdentities->offsetSet( "nesmysl" , $rabbitIdentity);
//    
//    }
//    
//    
//    
//     public function testOffsetGet(  ) : void { 
//        $rabbitIdentity =  new RabbitIdentityMock();       
//        $klicIdentity =  new KlicIdentityMock();        
//        $arrayI [ RabbitIdentityNamesEnumMock::RABBITIDENTITYINTERFACEMOCK ] =  $rabbitIdentity;
//        $arrayI [ RabbitIdentityNamesEnumMock::KLICIDENTITYINTERFACEMOCK ] = $klicIdentity;                
//        $rabbitIdentities = new Identities ( new RabbitIdentityNamesEnumMock(), $arrayI  ) ; 
//        
//        $rabbitIdentity1 = $rabbitIdentities->offsetGet( RabbitIdentityNamesEnumMock::RABBITIDENTITYINTERFACEMOCK );
//        $this->assertEquals($rabbitIdentity1, $rabbitIdentity);      
//       
//    }        
//    
    
}
