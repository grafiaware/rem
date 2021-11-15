<?php
namespace Model\RowObjectManager;

use Model\RowObject\RowObjectInterface;
use Model\RowObject\Key\KeyInterface;


/**
 *
 * @author vlse2610
 */
interface RowObjectManagerInterface {
    
    public function flush () : void ;
    

          
    /**
     * Vrací RowObject  vyhledany podle klice Key. Nebo vrati null.     
     * 
     * @param KeyInterface $key
     * @return RowObjectInterface|null
     */
    public function get(  $hash /*KeyInterface $key */ )  :  ?RowObjectInterface ;    
    
    
    /**
     * Vrací RowObject  vyhledany podle klice ForeignKey.  ?Nebo vrati null.?
     *     
     * @param KeyInterface $foreignKey
     * @return RowObjectInterface|null
     */
    public function getByForeignKey (  KeyInterface $foreignKey  )  : iterable /*?*/;
    
    
    /**
     * Odstraní RowObject z "RowObjectManagera".
     * @param RowObjectInterface $rowObject
     * @return void
     */
    public function remove( RowObjectInterface $rowObject ): void ;
                    
    /**
     * Přidá  RowObject do "RowObjectManagera", do úložiště.
     * 
     * @param RowObjectInterface $rowObject
     * @return void
     */
    public function add(  RowObjectInterface $rowObject): void ;


    //public function getIndexFromIdentityHash( array $identityHash ): string ;
 
    
    
    
    
    /**
     * Vytvoří nový RowObject.
     * 
     * @return RowObjectInterface
     */
    public function createRowObject (  ) : RowObjectInterface ;
        
     /**
     * Vytvoří nový Key.
     * 
     * @return Key
     */
    public function createKey (  ) : KeyInterface ;
    
       
    
}
