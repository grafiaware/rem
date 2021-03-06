<?php

//namespace Model\RowObjectManager;

use Model\RowObject\RowObjectInterface;
use Model\Hydrator\AttributeAccessHydratorInterface;

use Model\RowData\RowDataInterface;
use Model\RowData\PdoRowData;

use Model\Repository\Exception\BadImplemntastionOfChildRepository;
use Model\Repository\Exception\UnableAddRowObjectException;

use Model\Dao\DaoInterface;
use Model\Dao\DaoKeyDbVerifiedInterface;
use Model\Dao\Exception\DaoKeyVerificationFailedException;



/**
 * Description of RowObjectManagerAbstract
 *
 * @author vlse2610
 */
abstract class RowObjectManagerAbstract implements RowObjectManagerInterface{
//    /**
//     *
//     * @var array
//     */
//    protected $poleRowObjectu ;
    
    
    
    
    
    
    
    
    
    
    
//    public static $counter;
//    protected $count;
//    protected $oid;
//
//    protected $collection = [];
//    protected $new = [];
//    protected $removed = [];
//
//    private $flushed = false;
//
////    /**
////     *
////     * @var []
////     */
////    private $associations = [];
//
//    private $hydrators = [];
//    
//
//    /**
//     * @var DaoInterface 
//     */
//    protected $dao;
//   
//    
//    
//    protected function registerHydrator( AttributeHydratorInterface $hydrator ) {
//        $this->hydrators[] = $hydrator;
//    }
//
//    protected function hydrate( RowObjectInterface $rowObject, RowDataInterface $rowData ) {
//        foreach ($this->hydrators as $hydrator) {
//            $hydrator->hydrate( $rowObject, $rowData );
//        }
//    }
//
//    protected function extract( RowObjectInterface $rowObject,  RowDataInterface $rowData ) {
//        foreach ($this->hydrators as $hydrator) {
//            $hydrator->extract( $rowObject, $rowData );
//        }
//    }
//         

//    
//    
//    
//    
//    
//    public function flush(): void {
//        if ($this->flushed) {
//            return;
//        }
//        
//        if ( !($this instanceof RepoReadonlyInterface)) {
//
//            if ( ! ($this->dao instanceof DaoKeyDbVerifiedInterface)) {   // DaoKeyDbVerifiedInterface mus?? ukl??dat (insert) v??dy ji?? p??i nastavov??n?? hodnoty prim??rn??ho kl????e
//                foreach ($this->new as $rowObject) {                    
//                     //createRowData
//                    $rowData= new PdoRowData(); //$row = [];                    
//                    $this->extract($rowObject, $rowData);
//                    
//                    $this->dao->insert($rowData);
//                                                          
////                    $this->addAssociated($rowData, $rowObject);      // pridam mrkev
////                    $this->flushChildRepos();  //pokud je vno??en?? agregovan?? entita - mus?? se prov??st jej?? insert
//                    
//                    $rowObject->setPersisted();
//                }
//            }
//            $this->new = []; // p??i dal????m pokusu o find se bude volat recteateEntity, entita se zp??tn?? na??te z db (v??etn?? p????padn??ho autoincrement id a dal????ch generovan??ch sloupc??)
//
//            
//            foreach ($this->collection as $rowObject) {
//                 //createRowData
//                $rowData= new PdoRowData(); //$row = [];                    
//                $this->extract($rowObject, $rowData);
//                
////       /*??*/   $this->addAssociated($rowData, $rowObject);
////                $this->flushChildRepos();  //pokud je vno??en?? agregovan?? ...entita... p??id??na pozd??ji - mus?? se prov??st jej?? insert te??
//                if ($rowObject->isPersisted()) {
//                    if ($rowData) {     // $row po extractu mus?? obsahovat n??jak?? data, kter?? je mo??no updatovat - v extractu mus?? b??t vynech??ny "readonly" sloupce
//                        $this->dao->update($rowData);
//                    }
//                } else {
//                    throw new \LogicException("V collection je nepersistovan?? rowObject.");
//                }
//            }
//            $this->collection = [];
//            
//
//            foreach ($this->removed as $rowObject) {
//                //createRowData
//                $rowData= new PdoRowData(); //$row = [];
//                
//                $this->extract($rowObject, $rowData );
//                $this->removeAssociated($rowData, $rowObject);
//                $this->flushChildRepos();
//                $this->dao->delete( $rowData );
//                $rowObject->setUnpersisted();
//            }
//            $this->removed = [];
//
//        } else {
//            if ($this->new OR $this->removed) {
//                throw new \LogicException("Repo je read only a byly do n??j p??id??ny nebo z n??j smaz??ny rowObjecty");
//            }
//        }
//        $this->flushed = true;
//    }
//    
//    
}
