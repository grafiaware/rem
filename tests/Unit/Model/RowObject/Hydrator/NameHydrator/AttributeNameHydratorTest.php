<?php
namespace Test\RO_NameHydratorTest;

use PHPUnit\Framework\TestCase;

use Model\RowObject\Hydrator\NameHydrator\AttributeNameHydrator;



/**
 * Description of DaoTest
 *
 * 
 */
class ROAttributeNameHydratorTest extends TestCase {

    private  $testovanyCCStrings = [
        "testA",
        "janTamBylSam",
        "janVTomBylSam",
        "kadelJeJednicka",
        "predCislem123celych",
        "predCislem123Tisic",
        "nejdrivMalePotomTRDLO",
        "praZakladiVseho",
        "pra0zakLadi00Vseho",
        "pra0ZakLad3V3eho3",
        "123PraZaci",
        "ga_2ga_Ga_GA",
        "s_Podtrzitkem_",
        "sPodTrzitkem_",
        "s_Pod_trzitkem",
        "sPod_Trzitkem"
    ];
                 

    public function setUp(): void {  
        $x_JKKK ;        
    }
    
   
    public static function setUpBeforeClass(): void
    {
    }

    public static function tearDownAfterClass(): void
    {
    }
//------------------------------------------------------------------------------------------------
   
   public function testHydrate() {       
         $nameHydrator = new AttributeNameHydrator();
//       foreach ($this->testovany_Strings as $key => $value) {
//            $this->testOut[$key] = $nameHydrator->hydrate($value);
//       } 
        
        // ocekavana, aktualni
//        $this->assertEquals( "test_a", $nameHydrator->hydrate("test_a"), "-CHYBA-"  );
//        $this->assertEquals( "janTamBylSam", $nameHydrator->hydrate("jan_tam_byl_sam"), "-CHYBA-"  );
//        $this->assertEquals( "janVTomBylSam", $nameHydrator->hydrate("jan_v_tom_byl_sam"), "-CHYBA-"  );
//        $this->assertEquals( "kadelJeJednicka", $nameHydrator->hydrate("kadel_je_jednicka"), "-CHYBA-"  );
//        $this->assertEquals( "predCislem123celych", $nameHydrator->hydrate("pred_cislem123celych"), "-CHYBA-"  );
//        $this->assertEquals( "predCislem123Tisic", $nameHydrator->hydrate("pred_cislem123_tisic"), "-CHYBA-"  );
//        $this->assertEquals( "nejdrivMalePotomTRDLO", $nameHydrator->hydrate("nejdriv_male_potom_t_r_d_l_o"), "-CHYBA-"  );
//        $this->assertEquals( "praZakladiVseho", $nameHydrator->hydrate("pra_zakladi_vseho"), "-CHYBA-"  );
//        $this->assertEquals( "pra0zakLadi00Vseho", $nameHydrator->hydrate("pra0zak_ladi00_vseho"), "-CHYBA-"  );   
//        $this->assertEquals( "pra0ZakLad3V3eho3", $nameHydrator->hydrate("pra0_zak_lad3_v3eho3"), "-CHYBA-"  ); 
//        $this->assertEquals( "123PraZaci", $nameHydrator->hydrate("123_Pra_Zaci"), "-CHYBA-"  );
         
         
        $this->assertEquals( "test_a", $nameHydrator->extract("testA"), "-CHYBA-" );
        $this->assertEquals( "jan_tam_byl_sam",  $nameHydrator->extract("janTamBylSam"), "-CHYBA-" );
        $this->assertEquals( "jan_v_tom_byl_sam", $nameHydrator->extract("janVTomBylSam"), "-CHYBA-" );
        $this->assertEquals( "kadel_je_jednicka",  $nameHydrator->extract("kadelJeJednicka"), "-CHYBA-" );
        $this->assertEquals( "pred_cislem123celych", $nameHydrator->extract("predCislem123celych"), "-CHYBA-" );
        $this->assertEquals( "pred_cislem123_tisic",  $nameHydrator->extract("predCislem123Tisic"), "-CHYBA-" );
        $this->assertEquals( "nejdriv_male_potom_t_r_d_l_o",  $nameHydrator->extract("nejdrivMalePotomTRDLO"), "-CHYBA-" );        
        $this->assertEquals( "pra_zakladi_vseho", $nameHydrator->extract("praZakladiVseho"), "-CHYBA-" );
        $this->assertEquals( "pra0zak_ladi00_vseho", $nameHydrator->extract("pra0zakLadi00Vseho"), "-CHYBA-" );
        $this->assertEquals( "pra0_zak_lad3_v3eho3",  $nameHydrator->extract("pra0ZakLad3V3eho3"), "-CHYBA-" );
        $this->assertEquals( "123_pra_zaci", $nameHydrator->extract("123PraZaci"), "-CHYBA-" );
         
        
//        // nesmyslne, spatne nazvy sloupcu
//        $this->assertNotEquals( "sPodtrzitkem_", $nameHydrator->hydrate("s_podtrzitkem_"), "-CHYBA-"  );        
//        $this->assertEquals( "sPod22trzitkem", $nameHydrator->hydrate("s__pod2_2trzitkem"), "-CHYBA-"  ); 
//        $this->assertEquals( "sPodTrzitkem3", $nameHydrator->hydrate("s__pod__trzitkem3"), "-CHYBA-"  );   
   
       }   
       
   
    public function testExtract() {       
       $nameHydrator = new AttributeNameHydrator();
//       foreach ($this->testovanyCCStrings as $key => $value) {
//            $this->testOut[$key] = $nameHydrator->extract($value);
//       }   
       
        $this->assertEquals( "test_a", $nameHydrator->extract("testA"), "-CHYBA-" );
        $this->assertEquals( "jan_tam_byl_sam",  $nameHydrator->extract("janTamBylSam"), "-CHYBA-" );
        $this->assertEquals( "jan_v_tom_byl_sam", $nameHydrator->extract("janVTomBylSam"), "-CHYBA-" );
        $this->assertEquals( "kadel_je_jednicka",  $nameHydrator->extract("kadelJeJednicka"), "-CHYBA-" );
        $this->assertEquals( "pred_cislem123celych", $nameHydrator->extract("predCislem123celych"), "-CHYBA-" );
        $this->assertEquals( "pred_cislem123_tisic",  $nameHydrator->extract("predCislem123Tisic"), "-CHYBA-" );
        $this->assertEquals( "nejdriv_male_potom_t_r_d_l_o",  $nameHydrator->extract("nejdrivMalePotomTRDLO"), "-CHYBA-" );        
        $this->assertEquals( "pra_zakladi_vseho", $nameHydrator->extract("praZakladiVseho"), "-CHYBA-" );
        $this->assertEquals( "pra0zak_ladi00_vseho", $nameHydrator->extract("pra0zakLadi00Vseho"), "-CHYBA-" );
        $this->assertEquals( "pra0_zak_lad3_v3eho3",  $nameHydrator->extract("pra0ZakLad3V3eho3"), "-CHYBA-" );
        $this->assertEquals( "123_pra_zaci", $nameHydrator->extract("123PraZaci"), "-CHYBA-" );
        
//        //nesmyslne  -  nebudeme pouzivat promenne s podtrzitky       
//        $this->assertEquals( "s__podtrzitkem_",  $nameHydrator->extract("s_Podtrzitkem_"), "-CHYBA-" );  
//        $this->assertEquals( "s_pod_trzitkem_",  $nameHydrator->extract("sPodTrzitkem_"), "-CHYBA-" );
//        $this->assertEquals( "s__pod_trzitkem",  $nameHydrator->extract("s_Pod_trzitkem"), "-CHYBA-" );
//        $this->assertEquals( "s_pod__trzitkem",  $nameHydrator->extract("sPod_Trzitkem"), "-CHYBA-" );
     
    }
}


//        //asserts prohlasuje, tvrdi     assertion prohlaseni,tvrzeni
//        $this->assertEquals(1,1, "1 se nerovna 1");          
