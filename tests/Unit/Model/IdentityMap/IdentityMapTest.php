<?php
namespace Test\TestovaciRepositoryTest;

use PHPUnit\Framework\TestCase;

use Model\Hydrator\NameHydrator\AccessorMethodNameHydrator;
use Model\Filter\OneToOneFilter;
use Model\Hydrator\OneToOneAccessorHydrator;


use Model\Testovaci\Identity\RabbitIdentity;
use Model\Testovaci\Identity\KlicIdentity;
use Model\Testovaci\Identity\RabbitIdentityInterface;
use Model\Testovaci\Identity\KlicIdentityInterface;

use Model\Entity\Identities;
use Model\Testovaci\Entity\Enum\RabbitIdentityNamesEnum;
use Model\Testovaci\Entity\Enum\CarrotIdentityNamesEnum;
use Model\Testovaci\Entity\RabbitEntity;

use Model\IdentityMap\IdentityMap;
use Model\IdentityMap\IndexMaker\IndexMaker;


/**
 * Description of IdentityMapTest
 *
 * @author vlse2610
 */
class IdentityMapTest extends TestCase {
       
    
    public static function setUpBeforeClass(): void {          
    }        
    protected function setUp(): void {
    }
    
    
    
    
    public function test_Add_Has_Get_Remove() {               
        $methodNameHydrator = new AccessorMethodNameHydrator();     
        $indexMaker = new IndexMaker( $methodNameHydrator );        
                
        $rabbitIdentity = new RabbitIdentity(); 
        $rabbitIdentity->setId1( "01" );
        $rabbitIdentity->setId2( "02" );
        $poleJmenIdentityRabbit =   [ "id1", "id2" ];   

        $klicIdentity = new KlicIdentity();
        $klicIdentity->setKlic('klicVKralikovi');
        $poleJmenIdentityKlic =   [ "klic" ];   
        
        $rabbitIdentityTypesNames = new RabbitIdentityNamesEnum();
        $rabbitIdentities = new Identities( $rabbitIdentityTypesNames ); 
        $rabbitIdentities->append($rabbitIdentity);
        $rabbitIdentities->append($klicIdentity);
        
        $filterIdentityRabbit = new OneToOneFilter( $poleJmenIdentityRabbit);        
        $filtersI [RabbitIdentityNamesEnum::RABBITIDENTITYINTERFACE] = $filterIdentityRabbit;
        $filterIdentityKlic = new OneToOneFilter( $poleJmenIdentityKlic );        
        $filtersI [RabbitIdentityNamesEnum::KLICIDENTITYINTERFACE] = $filterIdentityKlic;
        
//lepeji mozna - mit pixlu na filtry  a pak i na hydratory 
// pixla na identity je identities
                
        $identityMap = new IdentityMap( $indexMaker, $filtersI );             
        $rabbitEntity = new RabbitEntity( $rabbitIdentities );              
            $rabbitEntity->setCeleJmeno("Jméno Celé"); 
            $rabbitEntity->setPrvekVarchar('');
            $rabbitEntity->setPrvekDatetime(new \DateTime('2000-01-01')); 
                      
        $identityMap->add($rabbitEntity);           
        $has = $identityMap->has( $rabbitIdentity, RabbitIdentityNamesEnum::RABBITIDENTITYINTERFACE);        
        $this->assertTrue($has);                    
        
        
        /** @var RabbitEntity $entity1 */
        $entity1 = $identityMap->get( $rabbitIdentity, RabbitIdentityNamesEnum::RABBITIDENTITYINTERFACE);        
        $this->assertInstanceOf( RabbitEntity::class, $entity1); 
        $entity1->setCeleJmeno( $entity1->getCeleJmeno() . "AA" );
        $has = $identityMap->has( $klicIdentity, RabbitIdentityNamesEnum::KLICIDENTITYINTERFACE);        
        $this->assertTrue($has);
        /** @var RabbitEntity $entity2 */
        $entity2 = $identityMap->get( $klicIdentity, RabbitIdentityNamesEnum::KLICIDENTITYINTERFACE);        
        $this->assertInstanceOf( RabbitEntity::class, $entity2);
        // ----- $rabbitEntity, entity1 a entity2 je ten samy objekt
        $this->assertEquals("Jméno CeléAA", $entity2->getCeleJmeno() );
        $this->assertEquals("Jméno CeléAA", $rabbitEntity->getCeleJmeno() );
               
        
        
         //---------------  jiny objekt (stejna identita) --- prepise v Identitz Map puvodni
        $rabbitEntityB = new RabbitEntity( $rabbitIdentities );              
            $rabbitEntityB->setCeleJmeno("Jméno Celé"); 
            $rabbitEntityB->setPrvekVarchar('');
            $rabbitEntityB->setPrvekDatetime(new \DateTime('2000-01-01')); 
        $identityMap->add($rabbitEntityB); 
        
        $entityB2 = $identityMap->get( $klicIdentity, RabbitIdentityNamesEnum::KLICIDENTITYINTERFACE);        
        $this->assertInstanceOf( RabbitEntity::class, $entityB2);
        // ----- $rabbitEntityB,  a entityB2 je ten samy objekt
        $this->assertEquals("Jméno Celé", $entityB2->getCeleJmeno() );
        //-------------------------------------------------------------------------
        
        
        
        
        $identityMap->remove($entity2);
        $has = $identityMap->has( $klicIdentity, RabbitIdentityNamesEnum::KLICIDENTITYINTERFACE);        
        $this->assertFalse($has);
    }          
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
//        $this->rabbitRepository->add($this->rabbitEntity1);
//        $this->assertFalse($this->rabbitEntity1->isPersisted() ); //
//       // $this->assertTrue($entity1->isLocked() ); //lock netestovat
//          
//        $this->expectException( OperationWithLockedEntityException::class ); //entita je zamknuta
//        $this->rabbitRepository->add($this->rabbitEntity1);
//                
//        $this->rabbitRepository->flush();          
//        $this->assertTrue($this->rabbitEntity1->isPersisted() );  //po flush je entita persistovana
//       // $this->assertFalse($entity1->isLocked() );  //lock netestovat    
//  
//    //--------------------------------------------------------------------------
//    
//    
//        $entity2 = $this->rabbitRepository->get($this->rabbitIdentity1);
//       
//        $this->assertContainsOnlyInstancesOf( RabbitEntity::class, [$entity2] );              
//        $this->assertInstanceOf(RabbitEntity::class, $entity2);  
//        
//        $this->assertTrue($entity2->isPersisted() );
//        //$this->assertFalse($entity2->isLocked() );   //lock netestovat
//        $this->assertEquals( "Jméno Celé" , $entity2->getCeleJmeno());
//        $e2Hodnota = $entity2->getPrvekDatetime();
//        $this->assertEquals ( '01-01-2000', $e2Hodnota->format( "d-m-Y" ) );
//        $this->assertEquals( "" ,  $entity2->getPrvekVarchar());                            
//        
//        /** @var RabbitIdentity $identity2 */
//        $this->assertEquals("66", $entity2->getIdentity()->getId1() );
//        $this->assertEquals("33", $entity2->getIdentity()->getId2() );
//        //-----------------------------------------------------        
//        
//        // takova entita v repository neni
//        $identity21 = new RabbitIdentity(); 
//           $identity21->setId1('66');         
//           $identity21->setId2('31') ;   
//        $entity3 = $this->rabbitRepository->get($identity21);   
//        $this->assertIsNotObject($entity3);
//        $this->assertNull($entity3);      
//    
    
  
    
} 