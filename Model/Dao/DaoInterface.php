<?php
namespace Model\Dao;

use Model\RowData\RowDataInterface;

/**
 *
 * @author pes2704
 */
interface DaoInterface {
    
    public function get( $asocPoleKlic ): ?RowDataInterface ;
    public function insert( RowDataInterface $rowData): void;
    public function update( RowDataInterface $rowData): void;
    public function delete( RowDataInterface $rowData ): void;
}
