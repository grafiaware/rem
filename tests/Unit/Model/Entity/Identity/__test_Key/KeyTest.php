<?php
namespace Test\__test_Key;

use PHPUnit\Framework\TestCase;

use Model\Entity\Identity\__k\Key;
use Model\Entity\Identity\__k\Exception\InvalidValueInGeneratedFieldException;
use Model\Entity\Identity\__k\Exception\IndexMismatchInKeyAttributtesException;
use Model\Entity\Identity\__k\Exception\AttemptToSetGeneratedKeyException;

/**
 * Description of KeyTest
 *
 * @author vlse2610
 */
class KeyTest extends TestCase  {     
    
    public function setUp(): void {        
    }        
    //-------------------------------------------------------------------------
   
    
    public function test_InvalidValueInGeneratedFieldException () : void {
        $testovaciHash1   = ['Klic1' => 'bb' , 'Klic2' => 'aa', 'Klic3' => '00'];
        $testovaciGenerated1 =  [ 'Klic1' => 1, 'Klic2' => false, 'Klic3' => false ];
        
        $this->expectException( InvalidValueInGeneratedFieldException::class ); 
        $key1 = new Key( $testovaciHash1, $testovaciGenerated1 );         
    }
    
    
    public function testSetHash_GetHash( ) : void  {
       $testovaciHash   = [ 'Klic1' => 'aa', 'Klic2' => 'bb'  ];
       $testovaciGenerated = [ 'Klic1' => false , 'Klic2' => false ];       
       $testovaciHash1   = [ 'Klic1' => 'aaaa', 'Klic2' => 'aabb'  ];
        
       $key = new Key( $testovaciHash, $testovaciGenerated );         
       $key->setHash ( $testovaciHash1 ) ;                     
       $hash = $key->getHash();                  
       $this->assertEquals( $testovaciHash1, $hash);
       
       // prohozeni v poli
       $testovaciHash   = [ 'Klic1' => 'aa', 'Klic2' => 'bb'  ];
       $testovaciGenerated = [ 'Klic2' => false, 'Klic1' => false ,  ];       
       $testovaciHash1   = [ 'Klic2' => 'aabb', 'Klic1' => 'aaaa' ];
       
       $key = new Key( $testovaciHash, $testovaciGenerated );         
       $key->setHash ( $testovaciHash1 ) ;                     
       $hash = $key->getHash();                  
       $this->assertEquals( $testovaciHash1, $hash);
       
    }    
    
    public function testSetHash_IndexMismatchInKeyAttributtesException() :void {
       $testovaciHash   = [ 'Klic1' => 'aa', 'Klic2' => 'bb'  ];
       $testovaciGenerated = [ 'Klic1' => false , 'Klic2' => false ];             
       //$testovaciHash1   = [ 'Klic1' => 'aaaa', 'Klic2' => 'aabb',  'Klic3'=>'' ];
       $testovaciHash1   = [ 'Klic1' => 'aaaa', 'Klic3' => 'aabb' ];
       
       $key = new Key( $testovaciHash, $testovaciGenerated );        
       $this->expectException( IndexMismatchInKeyAttributtesException::class );
       $key->setHash($testovaciHash1);        
    }
    
    
    public function testSetHash_AttemptToSetGeneratedKeyException()  : void  {
        $testovaciHash   = [ 'Klic1' => 'aa', 'Klic2' => 'bb'  ];
        $testovaciGenerated = [ 'Klic1' => false , 'Klic2' => true ]; 
        $testovaciHash1   = [ 'Klic1' => 'aaaa', 'Klic2' => 'aabb' ];
     
        $key = new Key( $testovaciHash, $testovaciGenerated );    
        $this->expectException( AttemptToSetGeneratedKeyException::class  );   
        $key->setHash( $testovaciHash1 ) ;              
    }
    
    
    public function testGetGenerated() : void   {
        $testovaciHash   = [ 'Klic1' => 'aa', 'Klic2' => 'bb'  ];
        $testovaciGenerated = [ 'Klic1' => false , 'Klic2' => true ]; 
        //$testovaciHash1   = [ 'Klic1' => 'aaaa', 'Klic2' => 'aabb' ];
        $key = new Key( $testovaciHash, $testovaciGenerated );
        
        $noveGenerated = $key->getGenerated();
        $this->assertEquals($testovaciGenerated, $noveGenerated); //ocekavana, aktualni
                //potrebuji ($noveGenerated===$testovaciGenerated)
    }
    
    
    
    public function testIsEqual(  ) : void  {
        $testovaciHash1   = [ 'Klic1' => 'bb' ,'Klic2' => 'aa',  'Klic3' => 'cc'];       
        $testovaciGenerated1 =  [ 'Klic1' => false, 'Klic2' => false, 'Klic3' => false ];
        
        $testovaciHash2   = ['Klic1' => 'bb' , 'Klic2' => 'aa', 'Klic3' => '00'];
        $testovaciGenerated2 =  [ 'Klic1' => false, 'Klic2' => false, 'Klic3' => false ];
        
        $testovaciHash3   = ['Klic1' => 'bb' , 'Klic2' => 'aa', 'Klic3' => '00'];
        $testovaciGenerated2 =  [ 'Klic1' => true, 'Klic2' => false, 'Klic3' => false ];
                    
        $key1 = new Key( $testovaciHash1, $testovaciGenerated1 );                
        $key2 = new Key( $testovaciHash1, $testovaciGenerated1 );       
        $this->assertTrue( $key1->isEqual($key2));
        
        $key1 = new Key( $testovaciHash1, $testovaciGenerated1 );                         
        $key2 = new Key( $testovaciHash2, $testovaciGenerated2 );       
        $this->assertFalse( $key1->isEqual($key2) );                                        
    }       
    
    
//    
//    
//    public function testGetAttribute() : void {
//        $testovaciHash   = [ 'Klic1' => 'aa', 'Klic2' => 'bb'  ];
//        $testovaciAttribute = [ 'Klic1' ,'Klic2'  ];
//        
//        $key = new Key( $testovaciAttribute );  
//        $this->assertEquals( $testovaciAttribute , $key->getAttribute());
//        
//        $key = new Key([]);  
//        $this->assertEquals( [] , $key->getAttribute());        
//    }
//    
//    
//    
//    public function testSetHash_GetHash( ) : void  {
//       $testovaciHash   = [ 'Klic1' => 'aa', 'Klic2' => 'bb'  ];
//       $testovaciAttribute = [ 'Klic1' ,'Klic2'  ];
//        
//       $key = new Key( $testovaciAttribute );            
//       $key->setHash ( $testovaciHash ) ;                     
//       $hash = $key->getHash();                  
//       $this->assertEquals( $testovaciHash, $hash);
//    }    
////    public function testGetHash( ) : void  {      
////    }        
//    
//    public function test_nullovy( ) : void  {
//       $testovaciHash   = [   ];
//       $testovaciAttribute = [   ];
//        
//       $key = new Key( $testovaciAttribute );            
//       $key->setHash ( $testovaciHash ) ;                     
//       $hash = $key->getHash();                  
//       $this->assertEquals( $testovaciHash, $hash );
//    }
//                   
//    public function testSetHash_MismatchedIndexesToKeyAttributeFieldsException() : void  {   // '*CH* neshodny attribute pri nastavovani klice'   
//       $testovaciHash   = [  'Klic2' => 'bb' , 'Klic1' => 'aa' ];
//       $testovaciAttribute = [ 'Klic2' ,'Klic3'  ];       
//       $key = new Key( $testovaciAttribute );    
//       $this->expectException( MismatchedIndexesToKeyAttributeFieldsException::class  );        //,  '*** klíče nesouhlasí ***'      
//       $key->setHash( $testovaciHash ) ;      
//       
//       $testovaciHash   = [  'Klic2' => 'bb' , 'Klic1' => 'aa' ];
//       $testovaciAttribute = [  ];       
//       $key = new Key( $testovaciAttribute );    
//       $this->expectException( MismatchedIndexesToKeyAttributeFieldsException::class );          //,  '*** attribute chybi ***'     
//       $key->setHash( $testovaciHash ) ;     
//       
//       $testovaciHash   = [  'Klic2' => 'bb' , 'Klic1' => 'aa' ];
//       $testovaciAttribute = [ 'Klic1' ,'Klic2' ];       
//       $key = new Key( $testovaciAttribute );    
//       $this->expectException( MismatchedIndexesToKeyAttributeFieldsException::class );          //, '*** prohozene poradi klice ***'
//       $key->setHash( $testovaciHash ) ;          
//    }
//
//
//    public function testIsEqual(  ) : void  {
//        $testovaciHash   = [  'Klic1' => 'bb' , 'Klic2' => 'aa' ];
//        $testovaciAttribute = [ 'Klic1' ,'Klic2'  ];   
//                    
//        $key1 = new Key( $testovaciAttribute ); 
//        $key1->setHash( $testovaciHash );
//        $key2 = new Key( $testovaciAttribute );
//        $key2->setHash( $testovaciHash );
//        $this->assertTrue( $key1->isEqual($key2), '*CH* hash ma byt equal, a neni ');
//        
//        $key1 = new Key( $testovaciAttribute ); 
//        $key1->setHash ( $testovaciHash );
//        $key2 = new Key(  [ 'Klic1' ] );
//        $key2->setHash ( [ 'Klic1' => 'aa'  ] );
//        $this->assertFalse( $key1->isEqual($key2), '*CH* hash nema byt equal, a je ');        
//    }   
//
//    
//    public function testHasEqualAttribute(  ) : void {
//        $testovaciHash   = [ 'Klic1' => 'bb' , 'Klic2' => 'aa' ];
//        $testovaciAttribute = [ 'Klic1' ,'Klic2'  ]; 
//        
//        $key1 = new Key( $testovaciAttribute ); 
//        $key2 = new Key( $testovaciAttribute );
//        $this->assertTrue( $key1->hasEqualAttribute($key2) , '*CH* attribute ma byt equal, a neni ');
//        
//        $key1 = new Key( $testovaciAttribute ); 
//        $key2 = new Key( [] );
//        $this->assertFalse( $key1->hasEqualAttribute($key2), '*CH* attribute nema byt equal, a je ');
//        
//        $key1 = new Key( $testovaciAttribute ); 
//        $key2 = new Key(  [ 'Klic1' ] );
//        $this->assertFalse( $key1->hasEqualAttribute($key2), '*CH* attribute nema byt equal, a je ');
//        
//        $key1 = new Key( ['aaaa', 'bbbb'] ); 
//        $key2 = new Key(  [ 'bbbb', 'aaaa' ] );
//        $this->assertFalse( $key1->hasEqualAttribute($key2), '*CH* attribute nema byt equal, a je ');        
//    }    
}
