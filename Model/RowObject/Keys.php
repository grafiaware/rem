<?php
namespace Model\RowObject;

//use Model\Entity\Enum\KeyTypeEnum;
use Model\RowObject\KeysInterface;

use Pes\Type\Exception\ValueNotInEnumException;

/**
 * Description of Kees
 * 
 * @author vlse2610
 */
class Keys extends \ArrayObject implements KeysInterface{
    
   
    private $keyTypes;

    /**
     * Konstruktor přijímá výčtový typ obsahující typy identit.
     *
     * <b>Doporučené použití:</b>
     * Objekt vytvořte bez parametru  $input  a jednotlivé hodnoty identit přidávejte -
     * a) voláním $promennaTypuIdentities['typIdentity'] = $identitaDanehoTypu;
     * b) volání $promennaTypuIdentities->offsetSet('typIdentity', $identitaDanehoTypu);
     * 
     * @param IdentityTypeEnum $identityTypes přípustné typy
     * @param array $input  pole identit
     * @param int $flags
     * @param string $iterator_class
     * @throws MismatchedIdentitiesException
     */        
    public function __construct(/*KeyTypeEnum*/ $keyTypes, array $input , int $flags = 0, string $iterator_class = "ArrayIterator") {
        $this->identityTypes = $keyTypes;                        
            
            $a = $this->keyTypes->getConstList();
                    
            foreach ( $a  as $idType) {   //pres enum
                if (!array_key_exists($idType /*key*/, $input)) {          
                    throw new MismatchedKeysException("V poli keys (input) nejsou všechny přípustné typy." );
                }
            }
            foreach ($input as $key => $val) {
                try {                    
                    $enum = $this->keyTypes;
                    $type = $enum($key);
                } catch (ValueNotInEnumException $exc) {
                    throw new MismatchedKeysException( "Typy v poli keys (input) neodpovidaji přípustným keys (jsou navíc)." );
                }
   
        }
        
        parent::__construct($input, $flags, $iterator_class);
    }
    
    /**
     * Kontroluje, je-li zadaný index přípustný. Pokud ano, zapise do objektu Identities na pozici $index dodanou hodnotu $newval.
     * 
     * Indexy odpovídají typu identity. 
     * Přípustné jsou indexy, které odpovídajídají některému typu identity v dané entitě. 
     * Seznam typů identit v dané entitě je zadán pomocí parametru konstruktoru typu IdentityTypeEnum.
     * Pro nepřípustný index metoda vyhazuje výjimku.
     * 
     * @param type $index
     * @param type $newval
     * @throws MismatchedIdentitiesException
     */
    public function offsetSet( /*string*/ $index, /*KeyInterface*/ $newval) {          
        //v predpisu metody v \ArrayObject nejsou uvedeny typy, tudiz neuvadet typy
        try {    
            $enum = $this->keyTypesTypes;
            $type = $enum($index);    // toto otestuje, je-li zadaný index  přípustný.
        } catch (ValueNotInEnumException $exc) {
            throw new MismatchedKeysException("Zadaný typ $index není přípustný." );
        }
        parent::offsetSet($type, $newval);
    }
}
