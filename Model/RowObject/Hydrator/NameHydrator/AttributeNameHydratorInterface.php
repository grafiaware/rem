<?php

namespace Model\RowObject\Hydrator\NameHydrator;

/**
 *
 * @author vlse2610
 */
interface AttributeNameHydratorInterface {
    public function hydrate($camelCaseName);
    public function extract($camelCaseName);
}
