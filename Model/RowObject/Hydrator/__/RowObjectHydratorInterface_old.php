<?php
//namespace Model\RowObject\Hydrator;

use Model\RowObject\RowObjectInterface;

/**
 *
 * @author vlse2610
 */
interface RowObjectHydratorInterface_old {

    /**
     * Extrahuje hodnoty z $rowObject do pole $row.
     * 
     * @param RowObjectInterface $rowObject
     * @param string $row array of
     * @return void
     * @throws Exception\UndefinedColumnNameException
     */
    public function extract( RowObjectInterface $rowObject, &$row): void;
    //&, tj. predani odkazem - aby byl ve volacim skriptu pristupny 
    //objekt je vzdy predavan odkazem
    
    
     /**
     * Hydratuje $rowObject hodnotami z pole $row. 
     * Pozadovany typ zjišťuje z tabulky db.
     * 
     * @param RowObjectInterface $rowObject
     * @param string $row array of
     * @return void
     */
    public function hydrate( RowObjectInterface $rowObject, &$row): void;  
   
}
