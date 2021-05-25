<?php

namespace Aggregate\Models\Hydrator;

use Aggregate\Models\Relation\RelationKeyInterface;

/**
 *
 * @author pes2704
 */
interface KeyHydratorInterface {

    /**
     * 
     * @param RelationKeyInterface $key
     * @param \ArrayAccess $data
     */
    public function hydrate(RelationKeyInterface $key, \ArrayAccess $data);

    /**
     * 
     * @param RelationKeyInterface $key
     * @param \ArrayAccess $data
     */
    public function extract(RelationKeyInterface $key, \ArrayAccess $data);
    
}
