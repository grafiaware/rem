<?php
////namespace Model\Entity\Identity;
//
//use Model\Entity\Identity\IdentityInterface;
//use Model\Entity\Identity\Exception\AttemptToSetGeneratedKeyException;
//
//use Model\Entity\Identity\Key\KeyInterface;
/**
 * Description of RelationKey
 *
 * @author pes2704
 */
class Identity implements IdentityInterface, \Serializable {
//    /**
//     *
//     * @var bool Klíč je generovaný.
//     */
//    private $hasGeneratedKey;
    
    /**
     *
     * @var KeyInterface 
     */
    private $key;
   
    /**
     * V konstruktoru nastaví parametry objektu Identity.
     * 
     * @param array $key - pole názvů částí klíče
     * @param bool $hasGeneratedKey - zda se jedná o klíč generovaný (nepovinný parametr)
     */    
    public function __construct ( KeyInterface $key /*   $hasGeneratedKey=FALSE*/ ) {
        //$this->hasGeneratedKey = (bool) $hasGeneratedKey;
        $this->key = $key;
    }

//    /**
//     * Vrací \TRUE  když klíč je generovaný.
//     * 
//     * @return bool
//     */
//    public function hasGeneratedKey() : bool {
//        return $this->hasGeneratedKey;
//    }

    
    /**
     * Vrací objekt klíče.
     * 
     * @return KeyInterface
     */
    public function getKey(): KeyInterface {
        return $this->key;
    }
 

    /**
     * Nastaví hodnoty klíče (key). Parametrem je asociativní pole, které musí mít stejné indexy jako atributte.
     * 
     * Metodu nelze použít, pokud klíč je generovaný - při pokusu o nastavení hodnot generovaného klíče metoda vyhazuje výjimku.
     * V případě generovaného klíče mohou být hodnoty klíče nastaveny pouze na hodnoty načtené z databáze.
     * Hodnoty generovaného klíče nastavuje IdentityHydrator. IdentityHydrator používá reflexi a tak překoná toto omezení.
     * 
     * @param array $keyHash Asociativní pole. Jednoprvkové pro simple key, n-prvkové pro compound (composite) key. Indexy musí odpovídat polím atributu.
     * @throws \AttemptToSetGeneratedKeyException Pokud dojde k pokusu o nastavení generovaného klíče.
     */      
    public function setKey( KeyInterface $key): void {
        if ($this->hasGeneratedKey) {
            throw new  AttemptToSetGeneratedKeyException('Klíč je generovaný a hodnoty generovaného klíče lze nastavit pouze hydrátorem při čtení z databáze.');           
            //throw new \LogicException('Klíč je generovaný a hodnoty generovaného klíče lze nastavit pouze hydrátorem při čtení z databáze.');
        }
        $this->key = $key;
    }

    
    
    //-----------------------------------------------------------------------------------        
    
    
    /**
     * Metoda rozhraní Serializable. Serializovanou hodnotu použít nař. jako index v kolekci. Je to také příprava pro případnu serializaci entity.
     * @return string
     */
    public function serialize() {
        return serialize(
                array(
                    'key' => $this->key,
                    'hasGeneratedKey' => $this->hasGeneratedKey,                    
                ));
    }
    /**
     * 
     * @param string $serialized
     */
    public function unserialize( $serialized ) {
        $data = unserialize($serialized);
        $this->key = $data['key'];
        $this->hasGeneratedKey = $data['hasGeneratedKey'];        
    }
}




   /**
     * Motivace:
     * Tabulka s kompozitním (správně kompoudním - compound) klíčem
     *
     * CREATE TABLE voting(QuestionID NUMERIC, MemberID NUMERIC);
     * nebo s primary key:
     * CREATE TABLE voting (QuestionID NUMERIC, MemberID NUMERIC, PRIMARY KEY (QuestionID, MemberID));
     *
     * můžeš volat:
     * SELECT * FROM voting WHERE QuestionID = 7 AND MemberID = 7
     * SELECT * FROM voting WHERE QuestionID = 7  (jen první část klíče - nutno použít postupně části klíče v pořadí zleva)
     * nelze volat:
     * SELECT * FROM voting WHERE MemberID = 7  (druhá část klíče v pořadí zleva)
     * a taky můžeš volat:
     * SELECT * FROM t WHERE (QuestionID, MemberID) IN ( (5,1), (7,2) );
     * pokud jsi nepoužil 'primary key', pak pro zrychlení:
     * CREATE INDEX voting_idx ON voting(QuestionID, MemberID); nebo CREATE UNIQUE INDEX voting_idx ON voting(QuestionID, MemberID);
     * a taky pro zrychlení i pro opačné pořadí zadaných klíčů CREATE UNIQUE INDEX voting_idx ON voting (MemberID, QuestionID);
     */
