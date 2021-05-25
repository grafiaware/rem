<?php

/**
 * Description of CoBudeVHydratoru
 *
 * @author vlse2610
 */
class CoBudeVHydratoru {
                if (isset($propertyValue) AND $propertyValue instanceof \Serializable) {  // null je vždy serializable
                    $row[$columnName] = serialize($propertyValue);
                } else {
                    
                    
            if ($columnType == 'char' OR $columnType == 'varchar' OR $columnType == 'text') {
                if ( $rowObject->$propertyName instanceof \Serializable) {
                     $rowObject->$propertyName = unserialize($columnValue);
                } else {
                    $rowObject->$propertyName = $columnValue;
                }
            } else
                }
}


//------------------------------------------------------------------
  
    public function getIterator() {
        return new \ArrayIterator(get_object_vars());
    }
    
    //    private function camelCaseToUnderscore($camelCaseName) {
        $pom = strtolower(preg_replace('/(?<!^)([A-Z])/', '_$1', $camelCaseName));
        return strtolower(preg_replace('/(?<!^)([A-Z])/', '_$1', $camelCaseName));  // RazDva -> raz_dva
    }

    private function underscoreToPascalCase($underscoredName){   // první písmeno velké  // raz_dva -> RazDva
        $pom = str_replace(' ', '', ucwords(str_replace('_', ' ', $underscoredName)));
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $underscoredName)));
    }