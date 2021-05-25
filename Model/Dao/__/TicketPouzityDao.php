<?php
//namespace Model\Dao;

use Model\RowObject\TicketPouzityRow;
use Model\RowObject\RowObjectInterface;

/**
 * Description of TicketPouzityDao
 *
 * @author vlse2610
 */
class TicketPouzityDao  extends DaoAbstract {
    
     /**
     * Metoda get v TicketPouzityDao
     * 
     * {@inheritdoc}
     * 
     * @param string $uid Identifikátor
     * @return RowObjectInterface|null 
     */
    public function get($uid): ?RowObjectInterface { 
        $sqlQuery = "SELECT  * FROM ticket_pouzity "
                  . "WHERE identifikator_ticketu = :identifikatorTicketu";                       
        $rowObject = $this->selectRowObject($sqlQuery, ['identifikatorTicketu'=>$uid], TicketPouzityRow::class);
        return $rowObject;
    }
        

     /**
     * Metoda find v TicketPouzityDao
     * 
     * {@inheritdoc}
     * 
     * @param string $sqlTemplateWhere SQL příkaz s placeholdery
     * @param array $poleNahrad Asociativní pole náhrad placeholderů
     * @return RowObjectInterface array of Pole objektů  typu RowObjectInterface (TicketPouzityRow)
     */
    public function find( $sqlTemplateWhere, array $poleNahrad ) {
        $sqlQuery = "SELECT  * "
                  . "FROM ticket_pouzity". ($sqlTemplateWhere ? ' WHERE '.$sqlTemplateWhere : '');             
        $RowObjects = $this->selectCollection($sqlQuery, $poleNahrad, TicketPouzityRow::class); 
        // Entity::class - jazykovy konstrukt tj.string oznacujici jmeno classy
        return $RowObjects;
    }  
    
    
  
    /**
     * Metoda save  v TicketPouzityDao
     * 
     * {@inheritdoc}      
     * 
     * @param RowObjectInterface $rowObject
     * @return void
     * @throws \UnexpectedValueException
     */
    public function save( RowObjectInterface $rowObject ): void {    
        if ( !($rowObject instanceof  TicketPouzityRow)) {
            throw new \UnexpectedValueException("Nesprávný typ parametru. Má být TicketPouzityRow, je to ". get_class($rowObject)."!");
        }
        if ($rowObject->isPersisted()) {
                $sqlQuery = "UPDATE ticket_pouzity SET "                    
                      . "identifikator_ticketu = :identifikatorTicketu "                                           
                      . "WHERE identifikator_ticketu = :identifikatorTicketu";       
                $this->execUpdate( $sqlQuery, $rowObject);
        } else {                                
                $sqlQuery = "INSERT INTO ticket_pouzity "
                            . "(identifikator_ticketu) "
                            . "VALUES (:identifikatorTicketu)" ;  
                $this->execInsertWithUid( $sqlQuery, $rowObject, 'identifikatorTicketu');                   
        }       
    }
              
      
    /**
     * Metoda delete v TicketPouzityDao
     * 
     * {@inheritdoc}
     * 
     * @param RowObjectInterface $rowObject
     * @return void
     * @throws \UnexpectedValueException
     */
    public function delete( TicketPouzityRow $rowObject) {
        if ( !($rowObject instanceof  TicketPouzityRow)) {
          throw new \UnexpectedValueException("Nesprávný typ parametru. Má být TicketPouzityRow, je to ". get_class($rowObject)."!");
        }
        $sqlQuery = "DELETE FROM ticket_pouzity "
                  . "WHERE identifikator_ticketu = :identifikatorTicketu";
        $this->execDelete($sqlQuery, $rowObject);   
        //nevrací nic
    }  
    
    
   
    
}
