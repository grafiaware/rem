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
    public function insert(RowDataInterface $rowData): void;
    public function update(RowDataInterface $rowData): void;
    public function delete(RowDataInterface $rowData): void;
}
