<?php
//namespace Model\Dao;

use Model\RowObject\SadaUlohRow ;
use Model\RowObject\RowObjectInterface;


/**
 * Description of SadaUlohDao
 *
 * @author vlse2610
 */
class SadaUlohDao  extends DaoAbstract  {
        
    /**
     * Metoda get v SadaUlohDao
     * 
     * {@inheritdoc}
     * 
     * @param string $uid Identifikátor
     * @return RowObjectInterface|null 
     */
    public function get( $uid ) {        //podle primarniho klice uid    
        $sqlQuery = "SELECT  * 
                     FROM sada_uloh                 
                     WHERE uid_nazev_sady = :uidNazevSady";                       
        $rowObject = $this->selectRowObject($sqlQuery, [ 'uidNazevSady'=>$uid ], SadaUlohRow::class);
        return $rowObject;
    }
   
    
    
    /**
     * Metoda find v SadaUlohDao
     * 
     * {@inheritdoc}
     * 
     * @param string $sqlTemplateWhere SQL příkaz s placeholdery
     * @param array $poleNahrad Asociativní pole náhrad placeholderů
     * @return RowObjectInterface array of Pole objektů  typu RowObjectInterface (SadaUlohRow)
     */
    public function find( $sqlTemplateWhere, array $poleNahrad ) {
        $sqlQuery = "SELECT  *
                     FROM sada_uloh". ($sqlTemplateWhere ? ' WHERE '.$sqlTemplateWhere : '');             
        $RowObjects = $this->selectCollection($sqlQuery, $poleNahrad, SadaUlohRow::class); 
                            // Entity::class - jazykovy konstrukt tj.string oznacujici jmeno classy
        return $RowObjects;
    }  
    
    
    
    /**
     * Metoda save  v SadaUlohDao
     * 
     * {@inheritdoc}      
     * 
     * @param RowObjectInterface $rowObject
     * @return void
     * @throws \UnexpectedValueException
     */
    public function save( RowObjectInterface $rowObject ): void {    
        if ( !($rowObject instanceof  SadaUlohRow)) {
            throw new \UnexpectedValueException("Nesprávný typ parametru. Má být SadaUlohRow, je to ". get_class($rowObject)."!");
        }
        if ($rowObject->isPersisted()) {
                $sqlQuery = "UPDATE sada_uloh SET "
                      . "uid_nazev_sady = :uidNazevSady "                                         
                      . "WHERE uid_nazev_sady = :uidNazevSady";
                $this->execUpdate( $sqlQuery, $rowObject);
        } else {                                
                $sqlQuery = "INSERT INTO sada_uloh "                                             
                       . "(uid_nazev_sady) "
                       . "VALUES ( :uidNazevSady )" ;   
                $this->execInsertWithUid( $sqlQuery, $rowObject, 'uid_nazev_sady');                                             
        }       
    }
    
    
    
    /**
     * Metoda delete v SadaUlohDao
     * 
     * {@inheritdoc}
     * 
     * @param RowObjectInterface $rowObject
     * @return void
     * @throws \UnexpectedValueException
     */
    public function delete(SadaUlohRow $rowObject) {
        if ( !($rowObject instanceof  SadaUlohRow)) {
            throw new \UnexpectedValueException("Nesprávný typ parametru. Má být SadaUlohRow, je to ". get_class($rowObject)."!");
        }
        $sqlQuery = "DELETE FROM sada_uloh "
                  . "WHERE id_sada_uloh = :idSadaUloh";
        $this->execDelete($sqlQuery, $rowObject);  
        //nevrací nic
    }  
    
    
    
}

