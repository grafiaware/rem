<?php
//namespace Model\RowObject\Hydrator;

use Model\RowObject\RowObjectInterface;
use Model\RowObject\Hydrator\NameHydratorInterface;

use Pes\Database\Metadata\ColumnMetadataInterface;
use Pes\Database\Metadata\TableMetadataInterface;

/**
 * Description of Hydrator
 *
 * @author vlse2610
 */
class RowObjectHydr implements RowObjectHydratorInterface_old{
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
     * @param NameHydratorInterface $nameHydrator
     * @param TableMetadataInterface $tableMetadata
     */
    public function __construct(NameHydratorInterface $nameHydrator, TableMetadataInterface $tableMetadata) {
        $this->nameHydrator = $nameHydrator;
        $this->tableMetadata = $tableMetadata;
    }
    
    
    
     /**
     * Hydratuje $rowObject hodnotami z pole sloupcu $row (směr do skriptu z úložiště).
     * - cyklus pres vsechny vlastnosti objektu $rowObject. Pozadovany typ zjišťuje z tabulky db.
     * (Ridime se vlastnostmi objektu, neex-li sloupec, tj. nejde "precist" - hlasit. Sloupec navic v db $row nam nevadi.)
     * 
     * @param RowObjectInterface $rowObject
     * @param string $row array of
     * @return void
     * @throws Exception\UnknownPropertyNameException
     * @throws Exception\DatetimeConversionFailureException
     */
    public function hydrate( RowObjectInterface $rowObject, &$row): void {  //&row(predani odkazem)   &-zde neni uplne nutne, cilem je vracet rowObject (objekt se predava automat.vzdy odkazem)
        //$properties = get_object_vars($rowObject);  // pole s vlastnostmi viditelnymi objektu
                   
        foreach ($rowObject as $propertyName=>$propertyValue) {  
            /*-nemusi byt explicitni -*/        $jmSloupce = $this->nameHydrator->extract($propertyName);
            /* @var $columnMetadata ColumnMetadataInterface  */
            $columnMetadata = $this->tableMetadata->getColumnMetadata($jmSloupce); // nemam sloupec v db           
            if ( !$columnMetadata) {
                throw new Exception\UndefinedColumnNameException("Neznámé (neex.) jméno sloupce $jmSloupce." );
            }        
 
            $columnType = $columnMetadata->getType();            
            /*-nemusi byt explicitni zde -*/     $hodnotaSl = $row[ $jmSloupce ] ;                          
          
            if (!isset( $row[ $jmSloupce ] )) {    
                $rowObject->$propertyName = NULL;
            } elseif ( $columnType == 'datetime' OR $columnType == 'timestamp') {
                $dat = \DateTime::createFromFormat("Y-m-d H:i:s",  $row[ $jmSloupce ] );
                if (!$dat) { throw new Exception\DatetimeConversionFailureException("Hodnota typu datetime ze sloupce $jmSloupce se nezkonvertovala." .
                             "do vlastnosti $propertyName rowObjektu.");  }                
                $rowObject->$propertyName = $dat ;
            } elseif ( $columnType == 'date') {
                $dat = \DateTime::createFromFormat("Y-m-d",  $row[ $jmSloupce ] );                
                if (!$dat) { throw new Exception\DatetimeConversionFailureException("Hodnota typu date ze sloupce $jmSloupce se nezkonvertovala.");  }                
                $dat->setTime(0, 0, 0, 0);
                $rowObject->$propertyName = $dat ;
            } else {
                $rowObject->$propertyName =  $row[ $jmSloupce ] ;
            }
                              
        }
    }    
    

    
    

    /**
     * Extrahuje hodnoty z $rowObject do pole sloupcu $row. (směr do uložiště)
     * - cyklus pres vsechny vlastnosti objektu $rowObject. Pozadovany typ zjišťuje z tabulky db.
     * (Ridime se vlastnostmi objektu, neex-li sloupec, tj. nejde "zapsat" - hlasit. Sloupce navic v db $row nevadi - nezjistujeme je.)
     * 
     * @param RowObjectInterface $rowObject
     * @param string $row array of
     * @return void
     * @throws Exception\UndefinedColumnNameException
     * @throws Exception\DatetimeConversionFailureException
     */    
    public function extract( RowObjectInterface $rowObject, &$row): void {      // &$row - zde je cilem vracet $row
        //&, tj. predani odkazem - aby byl ve volacim skriptu pristupny 
        //pozn. objekt je vzdy predavan odkazem
        foreach ($rowObject as $propertyName=>$propertyValue) {
            $columnName = $this->nameHydrator->extract($propertyName);
            /* @var $columnMetadata ColumnMetadataInterface  */
            $columnMetadata = $this->tableMetadata->getColumnMetadata($columnName);
            if ( !$columnMetadata) {
                throw new Exception\UndefinedColumnNameException("Název sloupce $columnName  není v tabulce {$this->tableMetadata->getTableName()}."
                . " {Název sloupce byl vyroben nameHydratorem z jména vlastnosti $propertyName rowObjektu " . get_class($rowObject) ."}");
            }
            $columnType = $columnMetadata->getType();
            $propertyValue = $rowObject->$propertyName;  //hodnota z objektu
            if (!isset($propertyValue)) {   //tady se resi null
                    $row[$columnName] = NULL;
            } elseif($columnType == 'datetime' OR $columnType=='timestamp') {
                if ($propertyValue instanceof \DateTime) {
                    $row[$columnName] = $propertyValue->format("Y-m-d H:i:s");
                } else {
                    throw new Exception\DatetimeConversionFailureException(
                         "Typ sloupce $columnName je datetime a hodnota vlastnosti " . get_class($rowObject) . "->$propertyName" .
                         " není instancí objektu \DateTime.");                      
                }
            } elseif ($columnType=='date') {
                if ($propertyValue instanceof \DateTime) {
                    $row[$columnName] = $propertyValue->format("Y-m-d"); //H:i:s netreba, potrebuji jen string obsahujici datum
                } else {
                    throw new Exception\DatetimeConversionFailureException(
                         "Typ sloupce $columnName je date a hodnota vlastnosti " . get_class($rowObject) . "->$propertyName" .
                         " není instancí objektu \DateTime.");                      
                }
            } else {
                $row[$columnName] = $propertyValue;
            }
        }
    }

    
}
