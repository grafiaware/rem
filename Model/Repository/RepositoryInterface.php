<?php

namespace Model\Repository;

/**
 *
 * @author pes2704
 */
interface RepositoryInterface {

    public function flush(): void;
    
    //protected function getIndexFromIdentityHash( array $identityHash ): string  ;
    
    
    
    //--------------------------------------------------
    public function getCollectionProTest(): array ;
    public function getNewProTest(): array ;
    public function getRemovedProTest(): array ;
    
    
}
