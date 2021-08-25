<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Model\Dao;

use Model\RowData\RowDataInterface;
use Model\RowObject\Key\KeyInterface;

/**
 *
 * @author pes2704
 */
interface DaoInterface {
    
    public function get(KeyInterface $key): RowDataInterface;
    
    
    /**
     * zapise insertem, a v pripade ze byla dogenerovavana nejake hodnoty, data,  refreshuje $rowData 
     * 
     * @param RowDataInterface $rowData
     * @return void
     */
    public function insert(RowDataInterface $rowData): void; 
    
    
    public function update(RowDataInterface $rowData): void;
    public function delete(RowDataInterface $rowData): void;
}
//podle ROMangera add, get, remove, createRowData ,,  update
