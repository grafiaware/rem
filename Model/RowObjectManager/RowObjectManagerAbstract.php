<?php

namespace Model\RowObjectManager;

use Model\RowObject\RowObjectInterface;
use Model\Hydrator\AttributeHydratorInterface;

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
abstract class RowObjectManagerAbstract {
    public static $counter;
    protected $count;
    protected $oid;

    protected $collection = [];
    protected $new = [];
    protected $removed = [];

    private $flushed = false;

    /**
     *
     * @var []
     */
    private $associations = [];

    private $hydrators = [];
    

    /**
     * @var DaoInterface | DaoKeyDbVerifiedInterface
     * @var  DaoKeyDbVerifiedInterface
     */
    protected $dao;
   
    
    
    protected function registerHydrator( AttributeHydratorInterface $hydrator ) {
        $this->hydrators[] = $hydrator;
    }

    protected function hydrate( RowObjectInterface $rowObject, RowDataInterface $rowData ) {
        foreach ($this->hydrators as $hydrator) {
            $hydrator->hydrate( $rowObject, $rowData );
        }
    }

    protected function extract( RowObjectInterface $rowObject,  RowDataInterface $rowData ) {
        foreach ($this->hydrators as $hydrator) {
            $hydrator->extract( $rowObject, $rowData );
        }
    }
         
    
    protected function createRowObject() {
        throw new BadImplemntastionOfChildRepository("Child repository must implement method createRowObject().");
    }
    

    
    
    
    
    
    
    
    
    
    
    public function flush(): void {
        if ($this->flushed) {
            return;
        }
        
        if ( !($this instanceof RepoReadonlyInterface)) {

            if ( ! ($this->dao instanceof DaoKeyDbVerifiedInterface)) {   // DaoKeyDbVerifiedInterface musí ukládat (insert) vždy již při nastavování hodnoty primárního klíče
                foreach ($this->new as $rowObject) {                    
                     //createRowData
                    $rowData= new PdoRowData(); //$row = [];                    
                    $this->extract($rowObject, $rowData);
                    
                    $this->dao->insert($rowData);
                                                          
//                    $this->addAssociated($rowData, $rowObject);      // pridam mrkev
//                    $this->flushChildRepos();  //pokud je vnořená agregovaná entita - musí se provést její insert
                    
                    $rowObject->setPersisted();
                }
            }
            $this->new = []; // při dalším pokusu o find se bude volat recteateEntity, entita se zpětně načte z db (včetně případného autoincrement id a dalších generovaných sloupců)

            
            foreach ($this->collection as $rowObject) {
                 //createRowData
                $rowData= new PdoRowData(); //$row = [];                    
                $this->extract($rowObject, $rowData);
                
//       /*??*/   $this->addAssociated($rowData, $rowObject);
//                $this->flushChildRepos();  //pokud je vnořená agregovaná ...entita... přidána později - musí se provést její insert teď
                if ($rowObject->isPersisted()) {
                    if ($rowData) {     // $row po extractu musí obsahovat nějaká data, která je možno updatovat - v extractu musí být vynechány "readonly" sloupce
                        $this->dao->update($rowData);
                    }
                } else {
                    throw new \LogicException("V collection je nepersistovaný rowObject.");
                }
            }
            $this->collection = [];
            

            foreach ($this->removed as $rowObject) {
                //createRowData
                $rowData= new PdoRowData(); //$row = [];
                
                $this->extract($rowObject, $rowData );
                $this->removeAssociated($rowData, $rowObject);
                $this->flushChildRepos();
                $this->dao->delete( $rowData );
                $rowObject->setUnpersisted();
            }
            $this->removed = [];

        } else {
            if ($this->new OR $this->removed) {
                throw new \LogicException("Repo je read only a byly do něj přidány nebo z něj smazány rowObjecty");
            }
        }
        $this->flushed = true;
    }
    
    
}
