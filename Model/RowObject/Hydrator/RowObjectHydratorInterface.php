<?php
namespace Model\RowObject\Hydrator;

use Model\RowObject\RowObjectInterface;
use Model\RowData\RowDataInterface;

/**
 *
 * @author vlse2610
 */
interface RowObjectHydratorInterface {
    
    /**
     * Hydratuje $rowObject hodnotami z objektu $rowData. 
     * Pozadovany typ zjišťuje z tabulky db.
     * 
     * @param RowObjectInterface $rowObject
     * @param RowDataInterface $rowData 
     * @return void
     */
    public function hydrate( RowObjectInterface $rowObject,  RowDataInterface $rowData  ): void;      
    

    /**
     * Extrahuje hodnoty z $rowObject do objektu $rowData.     
     * 
     * @param RowObjectInterface $rowObject
     * @param RowDataInterface $rowData 
     * @throws Exception\UndefinedColumnNameException     
     * @return void     
     */
    public function extract( RowObjectInterface $rowObject, RowDataInterface $rowData ): void;   
    
    
   
   
}
