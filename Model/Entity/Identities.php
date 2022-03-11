<?php
namespace Model\Entity;

use Model\Entity\Enum\IdentityTypeEnum;
use Model\Entity\IdentitiesInterface;
use Model\Entity\Identity\IdentityInterface;
use Model\Entity\Exception\MismatchedIdentitiesException;

use Pes\Type\Exception\ValueNotInEnumException;

use ArrayIterator;

/**
 * Description of Identities
 *
 * @author vlse2610
 */
class Identities implements IdentitiesInterface             /*tj. Traversable, IteratorAggregate*/{
    
    /**
     * @var IdentityTypeEnum výčtový typ. Obsahuje hodnoty názvú interfaců přípustných identit.
     */
    private $identityTypesEnum;
    /**
     * 
     * @var array Pole identit patřících entitě. Klíčem je  typ identity, tj. jméno interface identity IdentityInterface.
     */
    private $identities = [];
    
    /**
     * Konstruktor přijímá výčtový typ obsahující typy identit.
     *     
     */        
    public function __construct(IdentityTypeEnum $identityTypes ) {
        $this->identityTypesEnum = $identityTypes;                                      
    }
    
    
    /**
     * Přidá idetitu do pole This->identities. Kontroluje, je-li zadaná identity přípustného typu. 
     * 
     * Přípustné jsou typy, které odpovídají některému typu identity v dané entitě. 
     * Seznam typů identit v dané entitě je zadán pomocí parametru konstruktoru typu IdentityTypeEnum.
     * Pro nepřípustný typ metoda vyhazuje výjimku.
     * 
     * @param IdentityInterface $identity
     * @return void
     * @throws MismatchedIdentitiesException Nepřípustný typ identity
     */
    public function append(IdentityInterface $identity): void {          
        try {   
            $enum = $this->identityTypesEnum;
            $type = $enum($identity->getTypeIdentity());
            $this->identities[$type] = $identity;
        } catch (ValueNotInEnumException $exc) {
            throw new MismatchedIdentitiesException("Typ zadané identity {$identity->getTypeIdentity()} není přípustný." );
        }
    }        
  
        
    /**
     * Vrací iterátor $this->identities.
     * @return ArrayIterator $this->identities
     * @throws MismatchedIdentitiesException V případě, že v poli $this->identities chybí některý typ identity.
     */
    public function getIterator() {    
        foreach ( $this->identityTypesEnum->getConstList()  as $idType) {   //pres enum
            if (!array_key_exists($idType, $this->identities)) {          
                throw new MismatchedIdentitiesException("V poli identit nejsou všechny  typy. Chybí typ $idType ." );
            }
        }           
        return new ArrayIterator($this->identities);
    }
}
