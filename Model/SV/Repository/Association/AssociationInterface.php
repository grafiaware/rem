<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//namespace Model\Repository\Association;

/**
 *
 * @author pes2704
 */
interface AssociationInterface {

    public function flushChildRepo(): void;

}
