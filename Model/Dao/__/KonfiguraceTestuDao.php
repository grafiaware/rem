<?php
//namespace Model\Dao;

use Model\RowObject\KonfiguraceTestuRow;
use Model\RowObject\RowObjectInterface;

/**
 * Description of KonfiguraceTestuDao
 *
 * @author vlse2610
 */
class KonfiguraceTestuDao extends DaoAbstract  {

    /**
     * Metoda get v KonfiguraceTestuDao
     *
     * {@inheritdoc}
     *
     * @param string $uid Identifikátor
     * @return RowObjectInterface|null
     */
    public function get($uid): ?RowObjectInterface {
        $sqlQuery = "SELECT  *  FROM konfigurace_testu "
                   ."WHERE uid_konfigurace_testu = :uid_konfigurace_testu";
        $rowObject = $this->selectRowObject( $sqlQuery, ['uid_konfigurace_testu'=>$uid], KonfiguraceTestuRow::class);
        return $rowObject;
    }


    /**
     * Metoda find v KonfiguraceTestuDao
     *
     * {@inheritdoc}
     *
     * @param string $sqlTemplateWhere SQL příkaz s placeholdery
     * @param array $poleNahrad Asociativní pole náhrad placeholderů
     * @return RowObjectInterface array of Pole objektů  typu RowObjectInterface (KonfiguraceTestuRow)
     */
    public function find( $sqlTemplateWhere, array $poleNahrad ) {
        $sqlQuery = "SELECT  * "
                   ."FROM konfigurace_testu". ($sqlTemplateWhere ? ' WHERE ' . $sqlTemplateWhere : '');
        $rowObjects = $this->selectCollection($sqlQuery, $poleNahrad, KonfiguraceTestuRow::class);
                // Entity::class - jazykovy konstrukt tj.string oznacujici jmeno classy
        return $rowObjects;
    }


    /**
     * Metoda save  v KonfiguraceTestuDao
     *
     * {@inheritdoc}
     *
     * @param RowObjectInterface $rowObject
     * @return void
     * @throws \UnexpectedValueException
     */
    public function save( RowObjectInterface $rowObject ): void {
        if ( !($rowObject instanceof  KonfiguraceTestuRow)) {
            throw new \UnexpectedValueException("Nesprávný typ parametru. Má být KonfiguraceTestuRow, je to ". get_class($rowObject)."!");
        }
        if ($rowObject->isPersisted()) {
                $sqlQuery = "UPDATE konfigurace_testu SET "
                          . "popis_testu = :popis_testu, "
                          . "nazev_testu = :nazev_testu, "
                          . "paralel_v_session_spustitelny = :paralel_v_session_spustitelny, "
                          . "uid_nazev_sady_fk = :uid_nazev_sady_fk, "
                          . "valid = :valid "
                          . "WHERE uid_konfigurace_testu = :uid_konfigurace_testu";
                $this->execUpdate($sqlQuery, $rowObject);
        } else {
                $sqlQuery = "INSERT INTO konfigurace_testu  "
                  . "( uid_konfigurace_testu, popis_testu,  nazev_testu,  paralel_v_session_spustitelny, uid_nazev_sady_fk, valid) "
                  . "VALUES  ( :uid_konfigurace_testu, :popis_testu, :nazev_testu, :paralel_v_session_spustitelny, :uid_nazev_sady_fk, :valid )" ;
                $this->execInsertWithUid($sqlQuery, $rowObject, 'uid_konfigurace_testu');
        }
    }


    /**
     * Metoda delete v KonfiguraceTestuDao
     *
     * {@inheritdoc}
     *
     * @param RowObjectInterface $rowObject
     * @return void
     * @throws \UnexpectedValueException
     */
    public function delete( RowObjectInterface $rowObject): void {
        if ( !($rowObject instanceof  KonfiguraceTestuRow)) {
            throw new \UnexpectedValueException("Nesprávný typ parametru. Má být KonfiguraceTestuRow, je to ". get_class($rowObject)."!");
        }
        $sqlQuery = "DELETE FROM konfigurace_testu "
                  . "WHERE uid_konfigurace_testu = :uid_konfigurace_testu";
        $this->execDelete($sqlQuery, $rowObject);
        //nevrací nic
    }

}
// pozn. {@inheritdoc}    -  vlozi dlouhy popis z DaoInterface
