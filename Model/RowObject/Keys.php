<?php
namespace Model\RowObject;

//use Model\Entity\Enum\KeyTypeEnum;
use Model\RowObject\KeysInterface;
use Model\RowObject\Enum\KeyNameEnum;

use Model\RowObject\Key\KeyInterface;

//use Model\Entity\Enum\IdentityTypeEnum;
//use Model\Entity\IdentitiesInterface;
//use Model\Entity\Identity\IdentityInterface;
//use Model\Entity\Exception\MismatchedIdentitiesException;

//use Pes\Type\Exception\ValueNotInEnumException;

use ArrayIterator;


/**
 * Description of Keys
 * 
 * @author vlse2610
 */
class Keys implements KeysInterface {
 
    /**
     * @var KeyNameEnum výčtový typ. Obsahuje hodnoty názvú klíčú přípustných klíčů.
     */
    private $keyNameEnum;    

    private $keys = [];
    
    
    /**
     * Konstruktor přijímá výčtový typ obsahující názvy přípustných klíčů.
     * 
     * Kde se vezmou ?  -- z DB  --- cilene pojmenované  FieldName u Indexu a a foreign klicu   
     * Ma cenu zde zadavat?? -- nebo jen konrrolovat u append a get Iterator
     */        
    public function __construct( KeyNameEnum $keyNames ) {

        $this->keyNameEnum = $keyNames ;                                      
    }
    
    
    /**
     * Přidá idetitu do pole This->keys.
     * 
     * Seznam jmen klíčů v  daném rowobjektu je zadán pomocí parametru konstruktoru typu KeyNameEnum.
     * Pro nepřípustné jméno metoda vyhazuje výjimku.
     * 
     * @param IdentityInterface $identity
     * @return void
     * @throws MismatchedIdentitiesException Nepřípustný typ identity
     */
    public function append(  KeyInterface $key ): void {          
        try {   
            $enum = $this->keyNameEnum;
            $name = $enum($key->getNameKey() );
            $this->keys[$name] = $key;
        } catch (ValueNotInEnumException $exc) {
            throw new MismatchedIdentitiesException("Jméno {$key->getNameKey()} není přípustné." );
        }
    }        
  
        
    
    public function getIterator() {
     
        foreach ( $this->keyNameEnum->getConstList()  as $idName) {   //pres enum
            // jak vypada pole jmen klicu ? jsou tam 
            if (!array_key_exists($idName, $this->keys)) {          
               // throw new MismatchedIdentitiesException("V poli jmen klíčů nejsou všechny přípustná jména." );
            }
        }           
        return new ArrayIterator($this->identities);
    }
}

   
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
#############################################################################    
    
    
 //   public function offsetSet( /*string $index,*/ /*KeyInterface*/ $newval) {          
        //v predpisu metody v \ArrayObject nejsou uvedeny typy, tudiz neuvadet typy
        
//        try {    
//            $enum = $this->keyTypesTypes;
//            $type = $enum($index);    // toto otestuje, je-li zadaný index  přípustný.
//        } catch (ValueNotInEnumException $exc) {
//            throw new MismatchedKeysException("Zadaný typ $index není přípustný." );
//        }
        
        
        
//        parent::offsetSet($type, $newval);
//    }
//}
