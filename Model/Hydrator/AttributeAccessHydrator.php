<?php
namespace Model\Hydrator;

use Model\RowObject\AttributeInterface;

use Model\Hydrator\NameHydrator\AttributeNameHydratorInterface;
use Model\Hydrator\AttributeAccessHydratorInterface;
use Model\Filter\ColumnFilterInterface;
use Model\Hydrator\Exception\DatetimeConversionFailureException;
use Model\Hydrator\Exception\UndefinedColumnNameException;

use Model\RowData\RowDataInterface;

use Pes\Database\Metadata\ColumnMetadataInterface;
use Pes\Database\Metadata\TableMetadataInterface;

/**
 * Description of Hydrator
 *
 * @author vlse2610
 */

class AttributeAccessHydrator implements AttributeAccessHydratorInterface {
    /**
     *
     * @var AttributeNameHydratorInterface
     */
    private $nameHydrator;
           
    /**
     * @var TableMetadataInterface
     */
    private $tableMetadata;
    
    /**
     *
     * @var ColumnFilterInterface
     */
    private $columnFilter;
  
    
    /**
     * 
     * @param AttributeNameHydratorInterface $nameHydrator
     * @param TableMetadataInterface $tableMetadata
     * @param ColumnFilterInterface $columnFilter
     */
    public function __construct( AttributeNameHydratorInterface $nameHydrator, TableMetadataInterface $tableMetadata , 
                                 ColumnFilterInterface $columnFilter
                                ) {
        $this->nameHydrator  = $nameHydrator;
        $this->tableMetadata = $tableMetadata;
        $this->columnFilter  = $columnFilter;     
    }
    
    //TODO taky potebujeme vyresit readonly,   
    
     /**
     * Hydratuje $rowObject hodnotami z objektu $rowData  (směr do skriptu z úložiště).
     * - cyklus přes jména vlastností $rowObject - bere z filtru. Požadovany typ zjišťuje z tabulky db.
     * (Ridime se vlastnostmi objektu $rowObject, neex-li sloupec, tj. nejde "precist" - hlasí UndefinedColumnNameException. 
     * Sloupec navic v db ($rowData) nevadi.)
     * 
     * @param AttributeInterface $rowObject
     * @param RowDataInterface $rowData
     * @return void
     * @throws UndefinedColumnNameException
     * @throws DatetimeConversionFailureException
     */
    public function hydrate( AttributeInterface $rowObject,  RowDataInterface $rowData  ): void {        
                            //$properties = get_object_vars($rowObject);  // pole s vlastnostmi viditelnymi objektu              
        // filtr obsahuje jmena podle jmen vlastnosti rowObjektu  
        // rowDdata obsahuje jmena s podtrzitky
        
        foreach ( $this->columnFilter->getIterator() as $properName ) {  //dle zadaneho columnFiltru ...
        //    /*-nemusi byt explicitni - proto se pouzije nameHydrator */        
            $jmenoSloupce = $this->nameHydrator->hydrate($properName);       //  $properName je pro rowObject               
            
        /* @var $columnMetadata ColumnMetadataInterface  */
            $columnMetadata = $this->tableMetadata->getColumnMetadata( $jmenoSloupce );   
            if ( !$columnMetadata) {   // nemam sloupec v db 
                throw new UndefinedColumnNameException("Neznámé (neex.)jméno sloupce $properName." );
            }         
                    $columnType = $columnMetadata->getType();        
            
            
 //TATO KONTROLA SEM NEPATRI - tady se tohle nekontroluje --- asi v ??????            
//if (!isset( $rowData[ $jmenoSloupce ] )) {    // ev. zde pouzit objekt convertor  - jmeno sl., typ=v metadata, hodnota                
//    throw new UncompleteKeyException("Nekompletní klíč." );
//    //$key->$properName = NULL;
            
            if (!isset( $rowData[ $jmenoSloupce ] )) {    // ev. zde pouzit objekt convertor  - jmeno sl., typ=v metadata, hodnota
                $rowObject->$properName = NULL;
                
            } elseif ( $columnType == 'datetime' OR $columnType == 'timestamp') {
                $dat = \DateTime::createFromFormat("Y-m-d H:i:s",  $rowData[ $jmenoSloupce ] );   //vyrabim objekt DateTime
                if (!$dat) { throw new DatetimeConversionFailureException("Hodnota typu datetime ze sloupce $properName se nezkonvertovala." .
                             "do vlastnosti $jmenoSloupce rowObjektu.");  }                
                $rowObject->$properName = $dat ;
                
            } elseif ( $columnType == 'date') {
                $dat = \DateTime::createFromFormat("Y-m-d",  $rowData[ $jmenoSloupce ] );        //vyrabim objekt DateTime        
                if (!$dat) { throw new DatetimeConversionFailureException("Hodnota typu date ze sloupce $properName se nezkonvertovala.");  }                
                $dat->setTime(0, 0, 0, 0);
                $rowObject->$properName = $dat ;
                
            } else {
                $rowObject->$properName =  $rowData[ $jmenoSloupce ] ;
            }                              
        }
                
    }    
    

    
    

    /**
     * Extrahuje hodnoty z $rowObject do objektu $rowData . (směr do uložiště)
     * - cyklus přes všechny vlastnosti objektu $rowObject. Požadovaný typ zjišťuje z tabulky db.
     * (Ridime se vlastnostmi objektu $rowObject, neex-li sloupec, tj. nejde "zapsat" - hlasit. Sloupce navic v db $row nevadi - nezjistujeme je.)
     *
     * @param AttributeInterface $rowObject
     * @param RowDataInterface $rowData
     * @return void
     * @throws UndefinedColumnNameException
     * @throws DatetimeConversionFailureException
     */
    public function extract( AttributeInterface $rowObject, RowDataInterface $rowData ): void   { // zde je cilem vracet $rowData         
        // filtr obsahuje jmena podle jmen vlastnosti rowObjektu  
        // row data obsahuje jmena s podtrzitky
        
        foreach ( $this->columnFilter->getIterator() as $propertyNameROData ) { 
           $columnName = $this->nameHydrator->extract( $propertyNameROData );          
           
            /* @var $columnMetadata ColumnMetadataInterface  */
            $columnMetadata = $this->tableMetadata->getColumnMetadata( $columnName );
            if ( !$columnMetadata) {
//                throw new Exception\UndefinedColumnNameException("Název sloupce $columnName $jmSloupce  není v tabulce {$this->tableMetadata->getTableName()}."
//                . " {Název sloupce byl vyroben nameHydratorem z jména vlastnosti $propertyName rowObjektu " . get_class($rowObject) ."}");
                throw new UndefinedColumnNameException("Název sloupce  $columnName  není v tabulce {$this->tableMetadata->getTableName()}." );
            }
            $columnType = $columnMetadata->getType();
           
            $PropertyROValue = $rowObject->$propertyNameROData;  //hodnota z rowObjektu
            if (!isset($PropertyROValue)) {   //tady se resi null
                    $rowData[$columnName] = NULL;
                    
            } elseif($columnType == 'datetime' OR $columnType == 'timestamp') {
                if ($PropertyROValue instanceof \DateTime) {
                    $rowData[$columnName] = $PropertyROValue->format("Y-m-d H:i:s");
                } else {
                    throw new DatetimeConversionFailureException(
                         "Typ sloupce $columnName je datetime a hodnota vlastnosti " . get_class($rowObject) . "->$columnName" .
                         " není instancí objektu \DateTime.");                      
                }
            } elseif ($columnType == 'date') {
                if ($PropertyROValue instanceof \DateTime) {
                    $rowData[$columnName] = $PropertyROValue->format("Y-m-d"); //H:i:s netreba, potrebuji jen string obsahujici datum
                } else {
                    throw new DatetimeConversionFailureException(
                         "Typ sloupce $columnName je date a hodnota vlastnosti " . get_class($rowObject) . "->$columnName" .
                         " není instancí objektu \DateTime.");                      
                }
            } else {
                $rowData[ $columnName ] = $PropertyROValue;
            }
        }        
        
        
    }

    
}
