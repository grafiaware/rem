<?php
//namespace Model\RowObject\Hydrator;

//use Model\RowObject\RowObjectInterface;
use Model\RowObject\Key\KeyInterface;
use Model\RowObject\Hydrator\NameHydrator\AttributeNameHydratorInterface;

use Model\RowObject\Hydrator\Filter\ColumnFilterInterface;

use Model\RowData\RowDataInterface;
use Model\RowObject\Hydrator\Exception\DatetimeConversionFailureException;
use Model\RowObject\Hydrator\Exception\UndefinedColumnNameException;
use Model\RowObject\Hydrator\Exception\UncompleteKeyException;

use Pes\Database\Metadata\ColumnMetadataInterface;
use Pes\Database\Metadata\TableMetadataInterface;


/**
 * Description of Hydrator
 *
 * @author vlse2610
 */
class KeyRowObjectHydrator___ implements KeyRowObjectHydratorInterface___ {
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
     * @param ColumnFilterInterface $keyColumnFilter
     */
    public function __construct( AccessorNameHydratorInterface $nameHydrator, TableMetadataInterface $tableMetadata ,                                  
                                 ColumnFilterInterface $keyColumnFilter ) {
        $this->nameHydrator  = $nameHydrator;
        $this->tableMetadata = $tableMetadata;
        $this->columnFilter = $keyColumnFilter;
    }
    
    //TODO taky potebujeme vyresit readonly,   
    
   
    /**      
     * 
     * @param KeyInterface $key
     * @param RowDataInterface $rowData
     * @return void
     * @throws UncompleteKeyException
     * @throws DatetimeConversionFailureException
     */
    public function hydrate( KeyInterface $key,  RowDataInterface $rowData  ): void {        
                            //$properties = get_object_vars($rowObject);  // pole s vlastnostmi viditelnymi objektu              
        // filtr obsahuje jmena podle jmen vlastnosti rowObjektu->key
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
            if (!isset( $rowData[ $jmenoSloupce ] )) {    // ev. zde pouzit objekt convertor  - jmeno sl., typ=v metadata, hodnota                
                throw new UncompleteKeyException("Nekompletní klíč." );
                //$key->$properName = NULL;
                
            } elseif ( $columnType == 'datetime' OR $columnType == 'timestamp') {
                $dat = \DateTime::createFromFormat("Y-m-d H:i:s",  $rowData[ $jmenoSloupce ] );   //vyrabim objekt DateTime
                if (!$dat) { throw new DatetimeConversionFailureException("Hodnota typu datetime ze sloupce $properName se nezkonvertovala." .
                             "do vlastnosti $jmenoSloupce rowObjektu.");  }                
                $key->$properName = $dat ;
                
            } elseif ( $columnType == 'date') {
                $dat = \DateTime::createFromFormat("Y-m-d",  $rowData[ $jmenoSloupce ] );        //vyrabim objekt DateTime        
                if (!$dat) { throw new DatetimeConversionFailureException("Hodnota typu date ze sloupce $properName se nezkonvertovala.");  }                
                $dat->setTime(0, 0, 0, 0);
                $key->$properName = $dat ;
                
            } else {
                $key->$properName =  $rowData[ $jmenoSloupce ] ;
            }                              
        }                

        
    }    
    

    
    

    /**
     * Extrahuje hodnoty z $rowObject->$key do objektu $rowData . (směr do uložiště)
     * - cyklus přes všechny vlastnosti objektu $rowObject. Požadovaný typ zjišťuje z tabulky db.
     * (Ridime se vlastnostmi objektu $rowObject, neex-li sloupec, tj. nejde "zapsat do tabulky db" - hlasit. Sloupce navic v db $row nevadi - nezjistujeme je.)    
     * 
     * @param KeyInterface $key
     * @param RowDataInterface $rowData
     * @return void
     * @throws UndefinedColumnNameException
     * @throws DatetimeConversionFailureException
     */
    public function extract( KeyInterface $key, RowDataInterface $rowData ): void   { // zde je cilem vracet $rowData         
        // filtr obsahuje jmena podle jmen vlastnosti rowObjektu->Key
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
           
            $PropertyROValue = $key->$propertyNameROData;  //hodnota z rowObjektu->key
            if (!isset($PropertyROValue)) {   //tady se resi null
                throw new UncompleteKeyException("Nekompletní klíč." );
                    //$rowData[$columnName] = NULL;
                    
            } elseif($columnType == 'datetime' OR $columnType=='timestamp') {
                if ($PropertyROValue instanceof \DateTime) {
                    $rowData[$columnName] = $PropertyROValue->format("Y-m-d H:i:s");
                } else {
                    throw new DatetimeConversionFailureException(
                         "Typ sloupce $columnName je datetime a hodnota vlastnosti " . get_class($key) . "->$columnName" .
                         " není instancí objektu \DateTime.");                      
                }
            } elseif ($columnType=='date') {
                if ($PropertyROValue instanceof \DateTime) {
                    $rowData[$columnName] = $PropertyROValue->format("Y-m-d"); //H:i:s netreba, potrebuji jen string obsahujici datum
                } else {
                    throw new DatetimeConversionFailureException(
                         "Typ sloupce $columnName je date a hodnota vlastnosti " . get_class($key) . "->$columnName" .
                         " není instancí objektu \DateTime.");                      
                }
            } else {
                $rowData[ $columnName ] = $PropertyROValue;
            }
        }        
        
    }

    
}
