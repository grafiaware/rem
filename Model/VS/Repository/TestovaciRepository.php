<?php

namespace Model\VS\Repository;

use Model\VS\Entity\TestovaciEntityInterface;
use Model\VS\Identity\TestovaciIdentityInterface;


use Model\Repository\RepositoryAbstract;
use Model\Hydrator\AccessorHydratorInterface;
use Model\RowObjectManager\RowObjectManagerInterface;


/**
 * Description of TestovaciRepository
 *
 * @author vlse2610
 */
class TestovaciRepository extends RepositoryAbstract implements TestovaciRepositoryInterface  {
    
    
    function __construct( AccessorHydratorInterface $accessorHydrator,
                          RowObjectManagerInterface $rOManager
            ) {
        
        $this->registerHydrator( $accessorHydrator ); 
        $this->rowObjectManager = $rOManager;
        
    }
    
    
    public function add( TestovaciEntityInterface $entity): void {
        //$index = $this->indexFromEntity( $entity ); //je v addEntity
                
        $this->addEntity($entity);      
    }
    

    
    public function get( TestovaciIdentityInterface $identity):  TestovaciEntityInterface {
        $index = $this->indexFromIdentity($identity);
        if (!isset($this->collection[$index])) {
            //$rowData = new RowData(); 
            //$rowData = $this->dao->get( $identity->getKeyHash() ); // vraci konstantni pole - hodnoty z úložistě, $keyHash  zatim neni v metode get pouzito
      
            //nemam key
            $this->extractI( $identity, $key );
            
            $rowObject = $this->rowObjectManager->getRowObject( $key );
            //vyzvednout rowObject z managera
                        
            $this->recreateEntity( $index,  $rowObject ); // v abstractu,  
            // zarazeni do collection z uloziste( db, soubor , atd.... ), pod indexem  $index   
            // pozn. kdyz neni v ulozisti - asi neni ani $rowObject
        }
        
        return $this->collection[$index] ?? NULL;    
        
    }

    public function remove( TestovaciEntityInterface $entity): void {
        
    }

    
    
    /**
     * 
     * @return TestovaciEntityInterface
     */
//    public function createEntity() : TestovaciEntityInterface {
//        
//        //vyrobit prazdnou konkr. entity
//         return new TestovaciEntity ( new TestovacIdentity() ) ;
//        
//    }
        
    
            
    

}
