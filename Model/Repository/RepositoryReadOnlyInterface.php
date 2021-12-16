<?php

namespace Model\Repository;

/**
 *
 * @author vlse2610
 */
interface RepositoryReadOnlyInterface {
     public function flush(): void;
    
     public function __destruct() ;
     
     
     
     
     
     
    
    //--------------------------------------------------
//    public function getCollectionProTest(): array ;
//    public function getNewProTest(): array ;
//    public function getRemovedProTest(): array ;
}
