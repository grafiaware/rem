<?php
namespace Model\VS\RowObjectManager;

use Model\RowObjectManager\RowObjectManagerAbstract;
use Model\RowObjectManager\RowObjectManagerInterface;

use Model\RowObject\RowObjectInterface;
use Model\RowObject\Key\KeyInterface;

use Model\RowData\PdoRowData;



/**
 * Description of RowObjectManager
 *
 * @author vlse2610
 */
class TestovaciRowObjectManager  extends RowObjectManagerAbstract implements RowObjectManagerInterface{
         
    protected function indexFromRowObject(  RowObjectInterface $rowObject) {
        
        foreach ( ksort (\get_object_vars($rowObject->getKey()) ) as $nameAttr=>$value ) {            
           $index .= $value;                        
        }
        return $index;           
    }
    
    
    
    
     function addRowObject( RowObjectInterface $rowObject ): void {         
        if ($rowObject->isLocked()) {
            throw new OperationWithLockedEntityException("Nelze přidávat přidany nebo smazany rowObject.");
        }
        if ( $rowObject->isPersisted()) {
            $this->collection[$this->indexFromRowObject($rowObject)] = $rowObject;
        } else {
            $rowData= new PdoRowData(); 
            $this->extract($rowObject, $rowData);   
            $this->dao->insert($rowData);
            
            if  ($rowData->isChanged() ) {
                $this->hydrate($rowObject, $rowData);
                //popr.$rowData->fetchChanged();  a //hydratovat jen zmenene
                //zatim, vymazat
                $rowData->fetchChanged(); //vymaze pole changed
            }
            
            
// ' presunuto z Repository '
//            if ( $this->dao instanceof DaoKeyDbVerifiedInterface /*ulozHned*/ ) {  
//                //createRowData
//                $rowData= new PdoRowData(); //$row = [];
//                $this->extract($rowObject, $rowData);                                
//                // rezim overovany klic, i normalne
//                try {
//                    $this->dao->insertWithKeyVerification($rowData); 
//                    $rowObject->setPersisted();
//                    $this->collection[$this->indexFromRowObject($rowObject)] = $rowObject;
//                } catch ( DaoKeyVerificationFailedException $verificationFailedExc) {
//                    throw new UnableAddRowObjectException('RowObject s nastavenou hodnotou klíče nelze zapsat do databáze.', 0, $verificationFailedExc);
//                }
//            } else {
//                $this->new[] = $rowObject;
//                $rowObject->lock();               
//            }
                        
        }
        $this->flushed = false;
    }
    
    public function add( RowObjectInterface $rowObject ): void {         
    
    public function get( KeyInterface $key  )  :  RowObjectInterface {
         
    }    
    
    public function remove( RowObjectInterface $rowObject ): void {
        
    }

    //public function createKey () : KeyInterface {}
    
    public function createRowObject ( KeyInterface $key ) : RowObjectInterface {}
    
    
    
}
