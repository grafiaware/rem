<?php
namespace Test\KeyTest;

use PHPUnit\Framework\TestCase;

use Model\Entity\Identity\Key\Key;
use Model\Entity\Identity\Key\Exception\MismatchedIndexesToKeyAttributeFieldsException;

/**
 * Description of KeyTest
 *
 * @author vlse2610
 */
class KeyTest extends TestCase  {     
    
    public function setUp(): void {        
    }        
    //-------------------------------------------------------------------------
   
    
    
    public function testGetAttribute() : void {
        $testovaciHash   = [ 'Klic1' => 'aa', 'Klic2' => 'bb'  ];
        $testovaciAttribute = [ 'Klic1' ,'Klic2'  ];
        
        $key = new Key( $testovaciAttribute );  
        $this->assertEquals( $testovaciAttribute , $key->getAttribute());
        
        $key = new Key([]);  
        $this->assertEquals( [] , $key->getAttribute());        
    }
    
    
    
    public function testSetHash_GetHash( ) : void  {
       $testovaciHash   = [ 'Klic1' => 'aa', 'Klic2' => 'bb'  ];
       $testovaciAttribute = [ 'Klic1' ,'Klic2'  ];
        
       $key = new Key( $testovaciAttribute );            
       $key->setHash ( $testovaciHash ) ;                     
       $hash = $key->getHash();                  
       $this->assertEquals( $testovaciHash, $hash);
    }    
//    public function testGetHash( ) : void  {      
//    }        
    
    public function test_nullovy( ) : void  {
       $testovaciHash   = [   ];
       $testovaciAttribute = [   ];
        
       $key = new Key( $testovaciAttribute );            
       $key->setHash ( $testovaciHash ) ;                     
       $hash = $key->getHash();                  
       $this->assertEquals( $testovaciHash, $hash );
    }
                   
    public function testSetHash_MismatchedIndexesToKeyAttributeFieldsException() : void  {   // '*CH* neshodny attribute pri nastavovani klice'   
       $testovaciHash   = [  'Klic2' => 'bb' , 'Klic1' => 'aa' ];
       $testovaciAttribute = [ 'Klic2' ,'Klic3'  ];       
       $key = new Key( $testovaciAttribute );    
       $this->expectException( MismatchedIndexesToKeyAttributeFieldsException::class  );        //,  '*** klíče nesouhlasí ***'      
       $key->setHash( $testovaciHash ) ;      
       
       $testovaciHash   = [  'Klic2' => 'bb' , 'Klic1' => 'aa' ];
       $testovaciAttribute = [  ];       
       $key = new Key( $testovaciAttribute );    
       $this->expectException( MismatchedIndexesToKeyAttributeFieldsException::class );          //,  '*** attribute chybi ***'     
       $key->setHash( $testovaciHash ) ;     
       
       $testovaciHash   = [  'Klic2' => 'bb' , 'Klic1' => 'aa' ];
       $testovaciAttribute = [ 'Klic1' ,'Klic2' ];       
       $key = new Key( $testovaciAttribute );    
       $this->expectException( MismatchedIndexesToKeyAttributeFieldsException::class );          //, '*** prohozene poradi klice ***'
       $key->setHash( $testovaciHash ) ;          
    }


    public function testIsEqual(  ) : void  {
        $testovaciHash   = [  'Klic1' => 'bb' , 'Klic2' => 'aa' ];
        $testovaciAttribute = [ 'Klic1' ,'Klic2'  ];   
                    
        $key1 = new Key( $testovaciAttribute ); 
        $key1->setHash( $testovaciHash );
        $key2 = new Key( $testovaciAttribute );
        $key2->setHash( $testovaciHash );
        $this->assertTrue( $key1->isEqual($key2), '*CH* hash ma byt equal, a neni ');
        
        $key1 = new Key( $testovaciAttribute ); 
        $key1->setHash ( $testovaciHash );
        $key2 = new Key(  [ 'Klic1' ] );
        $key2->setHash ( [ 'Klic1' => 'aa'  ] );
        $this->assertFalse( $key1->isEqual($key2), '*CH* hash nema byt equal, a je ');        
    }   

    
    public function testHasEqualAttribute(  ) : void {
        $testovaciHash   = [ 'Klic1' => 'bb' , 'Klic2' => 'aa' ];
        $testovaciAttribute = [ 'Klic1' ,'Klic2'  ]; 
        
        $key1 = new Key( $testovaciAttribute ); 
        $key2 = new Key( $testovaciAttribute );
        $this->assertTrue( $key1->hasEqualAttribute($key2) , '*CH* attribute ma byt equal, a neni ');
        
        $key1 = new Key( $testovaciAttribute ); 
        $key2 = new Key( [] );
        $this->assertFalse( $key1->hasEqualAttribute($key2), '*CH* attribute nema byt equal, a je ');
        
        $key1 = new Key( $testovaciAttribute ); 
        $key2 = new Key(  [ 'Klic1' ] );
        $this->assertFalse( $key1->hasEqualAttribute($key2), '*CH* attribute nema byt equal, a je ');
        
        $key1 = new Key( ['aaaa', 'bbbb'] ); 
        $key2 = new Key(  [ 'bbbb', 'aaaa' ] );
        $this->assertFalse( $key1->hasEqualAttribute($key2), '*CH* attribute nema byt equal, a je ');        
    }    
}
