<?php
namespace Model\Entity;

use Model\Entity\Enum\IdentityTypeEnum;
use Model\Entity\IdentitiesInterface;

use Pes\Type\Exception\ValueNotInEnumException;
use Model\Entity\Exception\MismatchedIdentitiesException;

/**
 * Description of Identities
 *
 * @author vlse2610
 */
class Identities extends \ArrayObject implements IdentitiesInterface{
    
    /**
     * @var IdentityTypeEnum výčtový typ. Obsahuje hodnoty názvú interfaců přípustných identit.
     */
    private $identityTypes;

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
    public function __construct(IdentityTypeEnum $identityTypes, array $input , int $flags = 0, string $iterator_class = "ArrayIterator") {
        $this->identityTypes = $identityTypes;                        
            
            $a = $this->identityTypes->getConstList();
                    
            foreach ( $a  as $idType) {   //pres enum
                if (!array_key_exists($idType /*key*/, $input)) {          
                    throw new MismatchedIdentitiesException("V poli identit (input) nejsou všechny přípustné typy." );
                }
            }
            foreach ($input as $key => $val) {
                try {                    
                    $enum = $this->identityTypes;
                    $type = $enum($key);
                } catch (ValueNotInEnumException $exc) {
                    throw new MismatchedIdentitiesException( "Typy v poli identit (input) neodpovidaji přípustným identitám (jsou navíc)." );
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
    public function offsetSet( /*string*/ $index, /*IdentityInterface*/ $newval) {          
        //v predpisu metody v \ArrayObject nejsou uvedeny typy, tudiz neuvadet typy
        try {    
            $enum = $this->identityTypes;
            $type = $enum($index);    // toto otestuje, je-li zadaný index  přípustný.
        } catch (ValueNotInEnumException $exc) {
            throw new MismatchedIdentitiesException("Zadaný typ $index není přípustný." );
        }
        parent::offsetSet($type, $newval);
    }
}
