<?php

namespace Model\VS\RowObjectManager;

use Model\VS\RowObjectManager\TestovaciRowObjectManagerInterface;

use Model\RowObjectManager\RowObjectManagerAbstract;
use Model\RowObjectManager\RowObjectManagerInterface;


/**
 * Description of TestovaciRowObjectManager
 *
 * @author vlse2610
 */
class TestovaciRowObjectManager extends RowObjectManagerAbstract implements RowObjectManagerInterface {
    
//        
//    protected function indexFromRowObject(  RowObjectInterface $rowObject) {
//        
//        foreach ( ksort (\get_object_vars($rowObject->getKey()) ) as $nameAttr=>$value ) {            
//           $index .= $value;                        
//        }
//        return $index;           
//    }
//    
//    
//    
//    
//     function addRowObject( RowObjectInterface $rowObject ): void {         
//        if ($rowObject->isLocked()) {
//            throw new OperationWithLockedEntityException("Nelze přidávat přidany nebo smazany rowObject.");
//        }
//        if ( $rowObject->isPersisted()) {
//            $this->collection[$this->indexFromRowObject($rowObject)] = $rowObject;
//        } else {
//            
//// ' presunuto z Repository '
//
//            if ( $this->dao instanceof DaoKeyDbVerifiedInterface ) {  // rezim overovany klic
//                //createRowData
//                $rowData= new PdoRowData(); //$row = [];
//                $this->extract($rowObject, $rowData);
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
//                        
//        }
//        $this->flushed = false;
//    }
//    
//    
//     public function getRowObject( KeyInterface $key  )  :  RowObjectInterface {
//         
//     }
//    
//    
//    function removeRowObject( RowObjectInterface $rowObject ): void {
//        
//    }
//
//    public function createKey () : KeyInterface {}
//    
//    public function createRowObject ( KeyInterface $key ) : RowObjectInterface {}
//    
//    
}
