<?php
//namespace Model\Dao;

use Model\RowObject\OdpovedNaOtazkuRow;
use Model\RowObject\RowObjectInterface;


/**
 * Description of OdpovedNaOtazkuDao
 *
 * @author vlse2610
 */
class OdpovedNaOtazkuDao extends DaoAbstract {    
    
    /**
     * Metoda get v OdpovedNaOtazkuDao
     * 
     * {@inheritdoc}
     * 
     * @param integer $uid Identifikátor
     * @return RowObjectInterface|null 
     */    
    public function get( $uid ): ?RowObjectInterface {
        $sqlQuery = "SELECT  * FROM odpoved_na_otazku "
                  . "WHERE  id_odpoved_na_otazku = :uid";                       
        $RowObject = $this->selectRowObject($sqlQuery, ['uid'=>$uid], OdpovedNaOtazkuRow::class);
        return $RowObject;    
    }

     
    /**
     * Metoda find  v OdpovedNaOtazkuDao
     * 
     * {@inheritdoc}
     * 
     * @param string $sqlTemplateWhere SQL příkaz s placeholdery
     * @param array $poleNahrad Asociativní pole náhrad placeholderů
     * @return RowObjectInterface array of Pole objektů  typu RowObjectInterface (OdpovedNaOtazkuRow)
     */
    public function find( $sqlTemplateWhere, array $poleNahrad ) {
        $sqlQuery = "SELECT  * "
                  .  "FROM odpoved_na_otazku" . ($sqlTemplateWhere ? ' WHERE '.$sqlTemplateWhere : '');             
        $RowObjects = $this->selectCollection($sqlQuery, $poleNahrad, OdpovedNaOtazkuRow::class); 
                                                // Entity::class - jazykovy konstrukt tj.string oznacujici jmeno classy
        return $RowObjects;
        
    }

 
    /**
     * Metoda save v OdpovedNaOtazkuDao
     * 
     * {@inheritdoc}
     *  
     * @param RowObjectInterface $rowObject
     * @return void
     * @throws \UnexpectedValueException
     */
    public function save( RowObjectInterface $rowObject ): void {    
        if ( !($rowObject instanceof  OdpovedNaOtazkuRow)) {
            throw new \UnexpectedValueException("Nesprávný typ parametru. Má být OdpovedNaOtazkuRow, je to ". get_class($rowObject)."!");
        }
        if ($rowObject->isPersisted()) {                
                $sqlQuery = "UPDATE odpoved_na_otazku SET "
                          . "id_prubeh_testu_fk = :idPrubehTestuFk, "                                         
                          . "identifikator_odpovedi = :identifikatorOdpovedi, " 
                          . "hodnota = :hodnota"                          
                          . "WHERE id_sada_otazek = :idSadaOtazek";
                $this->execUpdate($sqlQuery, $rowObject);
        } else {                                                           
                $sqlQuery = "INSERT INTO odpoved_na_otazku "                                               
                           . "(id_prubeh_testu_fk, identifikator_odpovedi, hodnota ) " 
                           . "VALUES  ( :idPrubehTestuFk, :identifikatorOdpovedi, :hodnota )" ;  
                $this->execInsert($sqlQuery, $rowObject); 
        }       
    }
    
   
    
    /**
     * Metoda delete v OdpovedNaOtazkuDao
     * 
     * {@inheritdoc}
     * 
     * @param RowObjectInterface $rowObject
     * @return void 
     * @throws \UnexpectedValueException
     */    
    public function delete(  RowObjectInterface $rowObject): void {
        if ( !($rowObject instanceof OdpovedNaOtazkuRow)) {
            throw new \UnexpectedValueException("Nesprávný typ parametru. Má být OdpovedNaOtazkuRow, je to ". get_class($rowObject)."!");
        }
        $sqlQuery = "DELETE FROM odpoved_na_otazku "
                  . "WHERE id_odpoved_na_otazku = :idOdpovedNaOtazku";       
        $this->execDelete($sqlQuery, $rowObject );   
        //nevrací nic
    }
       
    
  
}
// pozn. {@inheritdoc}    -  vlozi dlouhy popis z DaoInterface