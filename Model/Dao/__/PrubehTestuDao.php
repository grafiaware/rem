<?php
//namespace Model\Dao;

use Model\RowObject\PrubehTestuRow;
use Model\RowObject\RowObjectInterface;

/**
 * Description of PrubehTestuDao
 *
 * @author vlse2610
 */
class PrubehTestuDao extends DaoAbstract {
                
    /**
     * Metoda get v PrubehTestuDao
     * 
     * {@inheritdoc}
     * 
     * @param int $id Identifikátor
     * @return RowObjectInterface|null 
     */
    public function get($uid): ?RowObjectInterface {      
        $sqlQuery = "SELECT * FROM prubeh_testu " 
                  . "WHERE id_prubeh_testu = :uid";                       
        $RowObject = $this->selectRowObject($sqlQuery,['uid'=>$uid], PrubehTestuRow::class);
        return $RowObject;
    }
    
        
     /**
     * Metoda find v PrubehTestuDao
     * 
     * {@inheritdoc}
     * 
     * @param string $sqlTemplateWhere SQL příkaz s placeholdery
     * @param array $poleNahrad Asociativní pole náhrad placeholderů
     * @return RowObjectInterface array of Pole objektů  typu RowObjectInterface (PrubehTestuRow)
     */
    public function find( $sqlTemplateWhere, array $poleNahrad ) {
        $sqlQuery = "SELECT * "
                  . "FROM prubeh_testu". ($sqlTemplateWhere ? ' WHERE '.$sqlTemplateWhere : '');
        $RowObjects = $this->selectCollection($sqlQuery, $poleNahrad, RowObject::class); 
                        // Entity::class - jazykovy konstrukt tj.string oznacujici jmeno classy
        return $RowObjects;
    }  
     
  
     /**
     * Metoda save  v PrubehTestuDao
     * 
     * {@inheritdoc}      
     * 
     * @param RowObjectInterface $rowObject
     * @return void
     * @throws \UnexpectedValueException
     */
    public function save( RowObjectInterface $rowObject ): void {    
        if ( !($rowObject instanceof  PrubehTestuRow)) {
            throw new \UnexpectedValueException("Nesprávný typ parametru. Má být PrubehTestuRow, je to ". get_class($rowObject)."!");
        }
        if ($rowObject->isPersisted()) {
                $sqlQuery = "UPDATE prubeh_testu SET "
                       . "uid_konfigurace_testu_fk = :uidKonfiguraceTestuFk, "
                       . "identifikator_ticketu_fk = :identifikatorTicketuFk, "
                       . "session_tabbedu = :sessionTabbedu, "
                       . "cas_spusteni = :casSpusteni, "  
                       . "cas_ukonceni = :casUkonceni, "                   
                       . "pole_navic = :poleNavic "
                       . "WHERE id_prubeh_testu = :idPrubehTestu";  
                $this->execUpdate($sqlQuery, $rowObject);
        } else {                                
                $sqlQuery = "INSERT INTO prubeh_testu "                                              
                        . "( identifikator_ticketu_fk, uid_konfigurace_testu_fk, " 
                        . "session_tabbedu, " 
                        . "cas_spusteni, cas_ukonceni, " 
                        . "pole_navic ) " 
                        . "VALUES ( :identifikatorTicketuFk, :uidKonfiguraceTestuFk, " 
                        . ":sessionTabbedu, " 
                        . ":casSpusteni, :casUkonceni,  " 
                        . ":poleNavic )" ;   
                $this->execInsert($sqlQuery, $rowObject);                   
        }       
    }
        
        
  
     /**
     * Odstraní objekt  typu RowObjectPrubehTestu z úložiště (databáze).
     * @param PrubehTestuRow $rowObject
     */
    
    /**
     * Metoda delete v PrubehTestuDao
     * 
     * {@inheritdoc}
     * 
     * @param RowObjectInterface $rowObject
     * @return void
     * @throws \UnexpectedValueException
     */
    public function delete(PrubehTestuRow $rowObject) {
        if ( !($rowObject instanceof  PrubehTestuRow)) {
            throw new \UnexpectedValueException("Nesprávný typ parametru. Má být PrubehTestuRow, je to ". get_class($rowObject)."!");
        }
        $sqlQuery = "DELETE FROM prubeh_testu "
                  . "WHERE id_prubeh_testu = :idPrubehTestu";
        $this->execDelete($sqlQuery, $rowObject);
        //nevrací nic
    }  
    
  
}
// pozn. {@inheritdoc}    -  vlozi dlouhy popis z DaoInterface
