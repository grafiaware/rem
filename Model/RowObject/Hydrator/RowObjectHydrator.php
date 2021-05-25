<?php
namespace Model\RowObject\Hydrator;

use Model\RowObject\RowObjectInterface;

use Model\RowObject\Hydrator\NameHydrator\AttributeNameHydratorInterface;
use Model\RowObject\Hydrator\RowObjectHydratorInterface;
use Model\RowObject\Hydrator\Filter\ColumnFilterInterface;
use Model\RowData\RowDataInterface;

use Pes\Database\Metadata\ColumnMetadataInterface;
use Pes\Database\Metadata\TableMetadataInterface;

/**
 * Description of Hydrator
 *
 * @author vlse2610
 */
class RowObjectHydrator implements RowObjectHydratorInterface {
    /**
     *
     * @var NameHydratorInterface
     */
    private $nameHydrator;
           
    /**
     * @var TableMetadataInterface
     */
    private $tableMetadata;
    
    /**
     *
     * @var ColumnFilter 
     */
    private $columnFilter;
    
    /**
     * 
     * @param NameHydratorInterface $nameHydrator
     * @param TableMetadataInterface $tableMetadata
     * 
     * 
     */
    public function __construct( AttributeNameHydratorInterface $nameHydrator, TableMetadataInterface $tableMetadata , 
                                 ColumnFilterInterface $columnFilter ) {
        $this->nameHydrator = $nameHydrator;
        $this->tableMetadata = $tableMetadata;
        $this->columnFilter = $columnFilter;
    }
    
    //TODO taky potebujeme vyresit readonly,   
    
     /**
     * Hydratuje $rowObject hodnotami z objektu $rowData  (směr do skriptu z úložiště).
     * - cyklus pres  jmena vlastnosti $rowObject - bere z filtru. Pozadovany typ zjišťuje z tabulky db.
     * (Ridime se vlastnostmi objektu $rowObject, neex-li sloupec, tj. nejde "precist" - hlasit. Sloupec navic v db ($rowData) nam nevadi.)
     * 
     * @param RowObjectInterface $rowObject
     * @param RowDataInterface $rowData
     * @return void
     * @throws Exception\UnknownPropertyNameException
     * @throws Exception\DatetimeConversionFailureException
     */
    public function hydrate( RowObjectInterface $rowObject,  RowDataInterface $rowData  ): void {        
                            //$properties = get_object_vars($rowObject);  // pole s vlastnostmi viditelnymi objektu              
        // filtr obsahuje jmena podle jmen vlastnosti rowObjektu  
        // row data obsahuje jmena s podtrzitky
        
        foreach ( $this->columnFilter->getIterator() as $properName ) {  //dle zadaneho filtru ...
        //    /*-nemusi byt explicitni - proto se pouzije nameHydrator */        
            $jmenoSloupce = $this->nameHydrator->hydrate($properName);       //  $propertyName je pro rowObject               
            
            /* @var $columnMetadata ColumnMetadataInterface  */
            $columnMetadata = $this->tableMetadata->getColumnMetadata( $jmenoSloupce );   
            if ( !$columnMetadata) {   // nemam sloupec v db 
                throw new Exception\UndefinedColumnNameException("Neznámé (neex.)jméno sloupce $properName." );
            }         
            $columnType = $columnMetadata->getType();                                                                                                      
            
            if (!isset( $rowData[ $jmenoSloupce ] )) {    // ev. zde pouzit objekt convertor  - jmeno sl., typ=v metadata, hodnota
                $rowObject->$properName = NULL;
                
            } elseif ( $columnType == 'datetime' OR $columnType == 'timestamp') {
                $dat = \DateTime::createFromFormat("Y-m-d H:i:s",  $rowData[ $jmenoSloupce ] );   //vyrabim objekt DateTime
                if (!$dat) { throw new Exception\DatetimeConversionFailureException("Hodnota typu datetime ze sloupce $properName se nezkonvertovala." .
                             "do vlastnosti $jmenoSloupce rowObjektu.");  }                
                $rowObject->$properName = $dat ;
                
            } elseif ( $columnType == 'date') {
                $dat = \DateTime::createFromFormat("Y-m-d",  $rowData[ $jmenoSloupce ] );        //vyrabim objekt DateTime        
                if (!$dat) { throw new Exception\DatetimeConversionFailureException("Hodnota typu date ze sloupce $properName se nezkonvertovala.");  }                
                $dat->setTime(0, 0, 0, 0);
                $rowObject->$properName = $dat ;
                
            } else {
                $rowObject->$properName =  $rowData[ $jmenoSloupce ] ;
            }
                              
        }
    }    
    

    
    

    /**
     * Extrahuje hodnoty z $rowObject do objektu $rowData . (směr do uložiště)
     * - cyklus pres vsechny vlastnosti objektu $rowObject. Pozadovany typ zjišťuje z tabulky db.
     * (Ridime se vlastnostmi objektu $rowObject, neex-li sloupec, tj. nejde "zapsat" - hlasit. Sloupce navic v db $row nevadi - nezjistujeme je.)
     * 
     * @param RowObjectInterface $rowObject
     * @param RowDataInterface $rowData 
     * @return void
     * @throws Exception\UndefinedColumnNameException
     * @throws Exception\DatetimeConversionFailureException
     */    
    public function extract( RowObjectInterface $rowObject, RowDataInterface $rowData ): void   { // zde je cilem vracet $rowData         
        // filtr obsahuje jmena podle jmen vlastnosti rowObjektu  
        // row data obsahuje jmena s podtrzitky
        
        foreach ( $this->columnFilter->getIterator() as $propertyNameROData ) { 
           $columnName = $this->nameHydrator->extract( $propertyNameROData );          
           
            /* @var $columnMetadata ColumnMetadataInterface  */
            $columnMetadata = $this->tableMetadata->getColumnMetadata( $columnName );
            if ( !$columnMetadata) {
//                throw new Exception\UndefinedColumnNameException("Název sloupce $columnName $jmSloupce  není v tabulce {$this->tableMetadata->getTableName()}."
//                . " {Název sloupce byl vyroben nameHydratorem z jména vlastnosti $propertyName rowObjektu " . get_class($rowObject) ."}");
                throw new Exception\UndefinedColumnNameException("Název sloupce  $columnName  není v tabulce {$this->tableMetadata->getTableName()}." );
            }
            $columnType = $columnMetadata->getType();
           
            $PropertyROValue = $rowObject->$propertyNameROData;  //hodnota z rowObjektu
            if (!isset($PropertyROValue)) {   //tady se resi null
                    $rowData[$columnName] = NULL;
                    
            } elseif($columnType == 'datetime' OR $columnType=='timestamp') {
                if ($PropertyROValue instanceof \DateTime) {
                    $rowData[$columnName] = $PropertyROValue->format("Y-m-d H:i:s");
                } else {
                    throw new Exception\DatetimeConversionFailureException(
                         "Typ sloupce $columnName je datetime a hodnota vlastnosti " . get_class($rowObject) . "->$columnName" .
                         " není instancí objektu \DateTime.");                      
                }
            } elseif ($columnType=='date') {
                if ($PropertyROValue instanceof \DateTime) {
                    $rowData[$columnName] = $PropertyROValue->format("Y-m-d"); //H:i:s netreba, potrebuji jen string obsahujici datum
                } else {
                    throw new Exception\DatetimeConversionFailureException(
                         "Typ sloupce $columnName je date a hodnota vlastnosti " . get_class($rowObject) . "->$columnName" .
                         " není instancí objektu \DateTime.");                      
                }
            } else {
                $rowData[ $columnName ] = $PropertyROValue;
            }
        }
    }

    
}
