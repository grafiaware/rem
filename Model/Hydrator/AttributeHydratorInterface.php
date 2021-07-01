<?php
namespace Model\Hydrator;

use Model\RowObject\AttributeInterface;
use Model\RowData\RowDataInterface;

/**
 *
 * @author vlse2610
 */
interface AttributeHydratorInterface {
    
    /**
     * Hydratuje $rowObject hodnotami z objektu $rowData. 
     * Pozadovany typ zjišťuje z tabulky db.
     * 
     * @param AttributeInterface $rowObject
     * @param RowDataInterface $rowData 
     * @return void
     */
    public function hydrate( AttributeInterface $rowObject,  RowDataInterface $rowData  ): void;      
    

    /**
     * Extrahuje hodnoty z $rowObject do objektu $rowData.     
     * 
     * @param AttributeInterface $rowObject
     * @param RowDataInterface $rowData 
     * @throws UndefinedColumnNameException     
     * @return void     
     */
    public function extract( AttributeInterface $rowObject, RowDataInterface $rowData ): void;   
    
    
   
   
}
