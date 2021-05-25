<?php
namespace Test\IdentityTest;

use PHPUnit\Framework\TestCase;

use Model\Entity\Identity\Identity;
use Model\Entity\Identity\Key\Key;
use Model\Entity\Identity\Exception\AttemptToSetGeneratedKeyException;


/**
 * Description of IdentityTest
 *
 * @author vlse2610
 */
class IdentityTest extends TestCase  {        
    
    public function setUp(): void {       
    }
    
    
    public function testHasGeneratedKey(  ) : void {
        $testovaciHash   = [ 'Klic1' => 'aa', 'Klic2' => 'bb'  ];
        $testovaciAttribute = [ 'Klic1' ,'Klic2'  ];   
        $testovaciKey = new Key( $testovaciAttribute ); 
        $testovaciKey->setHash($testovaciHash);

        $hasGeneratedKey = true; 
        $identity = new Identity( $testovaciKey, $hasGeneratedKey );        //na $attribute nezalezi       
        $this->assertTrue ( $identity->hasGeneratedKey() , '*CH* -  atribut objektu hasGeneratedKey ocekavan TRUE ' );

        $identity = new Identity( $testovaciKey, false );        //na $attribute nezalezi       
        $this->assertFalse ( $identity->hasGeneratedKey() , '*CH* -  atribut objektu hasGeneratedKey ocekavan FALSE ' );

        $identity = new Identity( $testovaciKey );        //na $attribute nezalezi       
        $this->assertFalse ( $identity->hasGeneratedKey() , '*CH* -  atribut objektu hasGeneratedKey ocekavan FALSE ' );
    } 
         
    
    public function testGetKey( ) : void  {
        $testovaciHash   = [ 'Klic1' => 'aa', 'Klic2' => 'bb'  ];
        $testovaciAttribute = [ 'Klic1' ,'Klic2'  ];   
        $testovaciKey = new Key( $testovaciAttribute ); 
        $testovaciKey->setHash($testovaciHash);
        
        $identity = new Identity( $testovaciKey );                                     
        $key = $identity->getKey();           
        $this->assertEquals( $testovaciKey, $key);
    }    
    
    
    public function test_nullovy( ) : void  {                
        $testovaciHash   = [   ];
        $testovaciAttribute = [   ];        
        $testovacikey = new Key( $testovaciAttribute );  
        $testovacikey->setHash( $testovaciHash );

        $identity = new Identity( $testovacikey );
        $identity->setKey ( $testovacikey ) ;                     
        $key = $identity->getKey();                  
        $this->assertEquals( $testovacikey, $key);
    }    
    
                        
    public function testSetKey_AttemptToSetGeneratedKeyException( ) : void  {
        $testovaciHash   = [ 'Klic1' => 'aa', 'Klic2' => 'bb'  ];
        $testovaciAttribute = [ 'Klic1' ,'Klic2'  ];   
        $testovaciKey = new Key( $testovaciAttribute ); 
        $testovaciKey->setHash($testovaciHash);
        
        $identity = new Identity( $testovaciKey, \TRUE );             
        $this->expectException( AttemptToSetGeneratedKeyException::class );  
        $identity->setKey( $testovaciKey ) ;  //'*CH* pokus o nastaveni klice, ktery je generovany '
    }    
 
}