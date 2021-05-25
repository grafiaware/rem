<?php
//
//namespace Model\Dao;
//
//
//use Model\RowObject\RowObjectInterface;
//use Model\RowObject\RowObjectOdpoved as RowObject;

/**
 * Description of Odpoved
 *
 * @author vlse2610
 */
class Odpoved extends DaoAbstract implements OdpovedInterface {
    
    /**
     * Nenajde-li, vrací NULL.
     * 
     * @param mixed $id
     * @return RowObject
     */
    public function get( $id) {            
        $sqlQuery = "SELECT  * 
                     FROM odpoved                 
                     WHERE  id_odpoved = :id";                       
        $entity = $this->selectRowObject($sqlQuery, array('id'=>$id), RowObject::class);
        return $entity;    
    }
    
    /**
     * 
     * @param type $idPrubehT
     * @return type Model\RowObject\Odpoved 
     */
    public function getByPrubehTestuId( $idPrubehT ) {            
        $sqlQuery = "SELECT  * 
                     FROM odpoved                 
                     WHERE  id_prubeh_testu_fk = :id";                       
        $entity = $this->selectRowObject($sqlQuery, array('id'=>$idPrubehT), RowObject::class);
        return  $entity ;
    }
    
     
    /**
     * {@inheritdoc}   
     * @param EntityInterface $entity
     */
    public function insert( RowObjectInterface $entity ) {
        if ( $entity->isPersisted()) {
            $sqlQuery = "UPDATE odpoved SET "                    
                      . "id_prubeh_testu_fk = :idPrubehTestuFk "                                           
                      . "session_tabbedu = :sessionTabbedu "                                           
                      . "WHERE id_odpoved = :idOdpoved";
            $this->update($sqlQuery, $entity);
        } else {
            $sqlQuery = "INSERT INTO odpoved                
                              ( id_prubeh_testu_fk, session_tabbedu) 
                         VALUES  ( :idPrubehTestuFk, :sessionTabbedu)" ;   
            $this->insert($sqlQuery, $entity);             
        }  
//    public $idOdpovedt;
//    public $idPrubehTestuFk;
//    public $inserted;
        
    }
    
    public function delete( RowObjectInterface $entity ) {
        $sqlQuery = "DELETE FROM odpoved "
                  . "WHERE $id_odpoved = :idOdpoved";
       
        $this->execDelete($sqlQuery, $entity); //vraci null     
    }
    
      public function deleteAll(){        
        $sqlQuery = "DELETE FROM odpoved "
                  . "WHERE 1=1";                 
        $this->destroyAll($sqlQuery); //vraci null    
        
    }
    
    
    
    /**
     * 
     * @param string $sqlTemplateWhere
     * @param array $poleNahrad
     * @return RowObject array of
     */         
    public function find( $sqlTemplateWhere, array $poleNahrad ) {
        $sqlQuery = "SELECT  *
                     FROM odpoved". ($sqlTemplateWhere ? ' WHERE '.$sqlTemplateWhere : '');             
        $entities = $this->selectCollection($sqlQuery, $poleNahrad, RowObject::class); 
        // Entity::class - jazykovy konstrukt tj.string oznacujici jmeno classy
        return $entities;
     
    }
    
}
