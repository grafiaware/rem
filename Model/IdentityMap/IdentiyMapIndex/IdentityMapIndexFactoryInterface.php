<?php

namespace Model\IdentityMap\IdentityMapIndex;

use Model\IdentityMap\IdentityMapIndex\IdentityMapIndexInterface;

/**
 *
 * @author vlse2610
 */
interface IdentityMapIndexFactoryInterface {

    public function create(): IdentityMapIndexInterface ;
    
    
}
