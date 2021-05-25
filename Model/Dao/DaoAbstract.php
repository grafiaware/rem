<?php
namespace Model\Dao;

use Model\RowObject\RowObjectInterface;
use Model\RowObject\Hydrator\RowObjectHydratorInterface;

use Pes\Database\Handler\HandlerInterface;
use Pes\Database\Metadata\MetadataProviderInterface;
use Pes\Database\Metadata\TableMetadataInterface;
//use Pes\Database\Metadata\ColumnMetadataInterface;

/**
 * Description of RepositoryAnstract
 *
 * @author vlse2610
 */
abstract class DaoAbstract implements DaoInterface{

    /**
     * @var HandlerInterface
     */
    private $dbh;

    private $tableName;


    /**
     * @var TableMetadataInterface
     */
    protected $tableMetadata;   //$tableMetadata -> columns[jm_sl]-objekty
    /**
     * @var HydratorInterface
     */
    private $hydrator;

    /**
     * Dao - data access object - objekt pro přístup k datům uloženým v databázi.
     *
     * Ukládá data z row objektů a načítá data do row objektů. Pro přavod dat načtenýh z datáze veformě pole do row objektu, který vrací
     * a pro převod dat z row objektu, který ukládá do formy pole ukládaného do databáze používá hydrátor. Hydrátor provádí automatický překlad jmen sloupců
     * databázové tabulky na jména vlastností row objektu a automatický převod některých typů databázových sloupců na vhodné PHP typy. Pro tyto převody
     * objekt dao potřebuje meta informace o databázové tabulce. Pro získání meta informací o tabulce používá MetadataProvider objekt.
     *
     * @param HandlerInterface $dbh Databázový handler
     * @param string $tableName Jméno tabulky
     * @param MetadataProviderInterface $metadataProvider Poskytovatel metadata informací o tabulce
     * @param RowObjectHydratorInterface $hydrator Objekt hydrator pro row object vracený metodami dao
     */
    public function __construct(HandlerInterface $dbh,
                                $tableName,
                                MetadataProviderInterface $metadataProvider,
                                RowObjectHydratorInterface $hydrator) {
        $this->dbh = $dbh;
        $this->tableName = $tableName;
        $this->tableMetadata = $metadataProvider->getTableMetadata($tableName); //$tableMetadata - columns[jm_sl]-objekty
        $this->hydrator = $hydrator;
    }


    /**
     *
     * @param string $sqlQuery SQL příkaz s placeholdery ve tvaru :placeholder
     * @param array $params Parametry pro bindValue - náhrady placeholderů
     * @param string $rowObjectClassName Jméno třídy RowObject, která bude vytvořena.
     * @return RowObjectInterface
     * @throws DbDaoException
     */
    protected function selectRowObject($sqlQuery, $params, $rowObjectClassName) {
        $statement = $this->dbh->prepare($sqlQuery);
        $this->bindStatementValues($statement, $params);
        if ($statement->execute()) {
            $poc = $statement->rowCount();
            if ($poc===1 ) {
                $rowObject = $this->createRowObject($statement->fetch(\PDO::FETCH_ASSOC), $rowObjectClassName);
            } elseif($statement->rowCount()>0) {
                throw new DbDaoException( '(selectRowObject) - Vybráno víc řádek podle id. - pro ' . $rowObjectClassName ,0,$e);
            }
        } else {
            throw new DbDaoException('(selectRowObject) - Selhal SQL příkaz select.',0,$e);
        }
        return $rowObject ?? NULL;
    }

    /**
     *
     * @param string $sqlQuery SQL příkaz s placeholdery ve tvaru :placeholder
     * @param array $params Parametry pro bindValue - náhrady placeholderů
     * @param string $rowObjectClassName Jméno třídy RowObject, která bude vytvořena.
     * @return RowObjectInterface array of
     * @throws DbDaoException
     */
    protected function selectCollection($sqlQuery, $params, $rowObjectClassName) {
        $statement = $this->dbh->prepare($sqlQuery);
        $this->bindStatementValues($statement, $params);
        if ($statement->execute()) {
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($result as $resultRow) {
                $rowObjects[] = $this->createRowObject($resultRow, $rowObjectClassName);
            }
        } else {
            throw new DbDaoException('(selectCollection) -  Selhal SQL příkaz select.',0, $e);
        }
        return $rowObjects ?? NULL;
    }

    /**
     * Vyrobí nový RowObject, naplní ho daty z řádku dat $row a nastaví jako persistovaný.
     *
     * @param array $row
     * @param string $rowObjectClassName jméno třídy vytvářeného RowObjectu
     * @return RowObjectInterface $rowObject
     */
    private function createRowObject($row,  $rowObjectClassName) {
        /* @var $rowObject RowObjectInterface */
        $rowObject = new $rowObjectClassName();
        $this->hydrator->hydrate($rowObject, $row);
        $rowObject->setPersisted(TRUE);
        return $rowObject;
    }

    /**
     * Insert pro tabulku s primarnim klicem generovanym skriptem (nikoli databazi jako autoincrement).
     * Metoda se používá u tabulek, kde je treba zachovani konzistence dat (napr. mezi databazemi...).
     *
     * @param string $sqlQuery SQL příkaz s placeholdery ve tvaru :placeholder
     * @param RowObjectInterface $rowObject
     * @param string $uidColumnName Jméno sloupce, do kterého se zapisuje uid
     * @throws DbDaoException
     */
    protected function execInsertWithUid($sqlQuery, RowObjectInterface $rowObject, $uidColumnName) {
        $row=[];
        $this->hydrator->extract($rowObject, $row);
        try {
            $this->dbh->beginTransaction();
            $uid = $this->getNewUidWithinTransaction($uidColumnName);
            $row[$uidColumnName] = $uid;
            $statement = $this->dbh->prepare($sqlQuery);
            $this->bindStatementValues($statement, $row);
            $statement->execute();
            $this->dbh->commit();
        } catch(\PDOException $e) {
            $this->dbh->rollBack();
            throw new DbDaoException('(execInsertWithUid) - Selhala transakce insert.',0,$e);
        }
        $row = [$uidColumnName=>$uid];
        $this->hydrator->hydrate($rowObject, $row);
        $rowObject->setPersisted(TRUE);
    }

    /**
     * Pro tabulky s autoinkrementalnim primarnim klicem.
     *
     * @param string $sqlQuery SQL příkaz s placeholdery ve tvaru :placeholder
     * @param RowObjectInterface $rowObject
     * @throws DbDaoException
     */
    protected function execInsert($sqlQuery, RowObjectInterface $rowObject) {
        $row=[];
        $this->hydrator->extract($rowObject, $row);
        try {
            $this->dbh->beginTransaction();

            $statement = $this->dbh->prepare($sqlQuery);
            $this->bindStatementValues($statement, $row);
            $statement->execute();
            $this->dbh->commit();
        } catch(\PDOException $e) {
            $this->dbh->rollBack();
            throw new DbDaoException('(execInsert) - Selhal SQL příkaz insert.',0,$e);
        }
        $row = $this->getRowWithId();
        $this->hydrator->hydrate($rowObject, $row);
        $rowObject->setPersisted(TRUE);
    }

    /**
     *
     * @param string $sqlQuery SQL příkaz s placeholdery ve tvaru :placeholder
     * @param RowObjectInterface $rowObject
     * @return boolean
     * @throws DbDaoException
     * @throws \Exception
     */
    protected function execUpdate($sqlQuery, RowObjectInterface $rowObject) {
        $row=[];
        $this->hydrator->extract($rowObject, $row);
        try {
            $this->dbh->beginTransaction();
            $statement = $this->dbh->prepare( $sqlQuery);
            $this->bindStatementValues($statement, $row);
            $statement->execute();
            $countR = $statement->rowCount();
            if ($countR > 1) {
                $this->dbh->rollBack();
                throw new DbDaoException('(execUpdate) - Pokus o update více než 1 řádku v '.get_called_class().'. Transakce zrušena.',0,$e);
            } else {
                $this->dbh->commit();
            }
        } catch(\PDOException $e) {  //vs.exception a jeji potomci
            $this->dbh->rollBack();
            throw new DbDaoException('(execUpdate) - Selhala transakce update v '.get_called_class() ,0,$e);
        }
        if ($countR === 0) {
            throw new DbDaoException('(execUpdate) - Nepodařil se update. Update 0 řádek.',0,$e);
        }
    }



    /**
     * Vykona DELETE v databazi a nastavi objekt jako neperzistovany tim, ze mu sebere id (tj. nastavi vlastnost id na NULL).
     *
     * @param string $sqlQuery SQL příkaz s placeholdery ve tvaru :placeholder
     * @param RowObjectInterface $rowObject
     * @throws DbDaoException
     */
    protected function execDelete($sqlQuery,  RowObjectInterface $rowObject) {
        $row=[];
        $this->hydrator->extract($rowObject, $row);
        try {
            $this->dbh->beginTransaction();
            $statement = $this->dbh->prepare($sqlQuery);
            $this->bindStatementValues($statement, $row);
            $statement->execute();   //!DULEZITE SDELENI! -timto prikazem vymazu ulozene vlastnosti  objektu (ty persistovane) z databaze.
            if ($statement->rowCount()>1) {
                throw new DbDaoException('(execDelete) - Pokus o delete více než 1 řádku v '.get_called_class().'. Transakce zrušena.',0,$e);
            }
            $this->dbh->commit();
        } catch(\Exception $e) {
            throw new DbDaoException('(execDelete) - Selhal SQL přikaz -nepodařil se v '.get_called_class() . '.', 0, $e);
        }
        if ($statement->rowCount()==0) {
            user_error('(execDelete) - Pokus o mazání neexistují řádky!', E_USER_NOTICE);
        }
        $this->hydrator->hydrate($rowObject, $this->getRowWithNullPrimaryKeyAttributes());
        $rowObject->setPersisted(false);
    }


    private function bindStatementValues(\PDOStatement $statement, array $row): void {
        $queryString = $statement->queryString;
        foreach ($row as $name=>$value) {
            $placeholder = ':'.$name;
            if (strpos($queryString, $placeholder) !== FALSE) {
                $statement->bindValue($placeholder, $value);
            }
        }
    }

    /**
     * Generuje uid unikátní v rámci tabulky.
     *
     * Generuje uid pomocí PHP funkce uniqid() a ověří, že vygenerované uid skutečně není v tabulce dosud použito. Pokud je použito, generuje nové uid.
     * Aby byla zaručena unikátnost uid v rámci jedné tabulky, je nutné, aby čtení tabulky při zjišťování existence uid a následný zápis nového
     * záznamu proběhly se zamčenou tabulkou.
     * Tato metoda používá příkaz "SELECT uid FROM table WHERE uid = :uid LOCK IN SHARE MODE", který zamkne přečtené záznamy až
     * do okamžiku ukončení transakce. Proto lze tuto metodu použít jen uprostřed již spuštěné transakce.
     *  Volání této metody mimo spuštěnou transakci vyvolá výjimky.
     */
    private function getNewUidWithinTransaction($uidColumnName) {
        if ($this->dbh->inTransaction()) {
            $placeholder = ':'.$uidColumnName;
            if ($this->tableMetadata->getColumnMetadata($uidColumnName)) {
                do {
                    $uid = uniqid();
                    $stmt = $this->dbh->prepare(
                        "SELECT $uidColumnName
                        FROM $this->tableName
                        WHERE $uidColumnName = $placeholder LOCK IN SHARE MODE");   //nelze použít LOCK TABLES - to commitne aktuální transakci!
                    $stmt->bindParam($placeholder, $uid);
                    $stmt->execute();
                } while ($stmt->rowCount());
                return $uid;
            } else {
                throw new \UnexpectedValueException("(getNewUidWithinTransaction) - Zadané jméno vlastnosti $uidColumnName vede na neexistující sloupec $uidColumnName.");
            }
        } else {
            throw new \LogicException('(getNewUidWithinTransaction) - Tuto metodu lze volat pouze uprostřed spuštěné databázové transakce.');
        }
    }


    /**
     * Nastaví last insert id tomu atributu primárního klíče, který je automaticky generovaný.
     *
     * @param array $row
     */
    private function getRowWithId() {
        $row=[];
        foreach ($this->tableMetadata->getPrimaryKeyAttribute() as $name) {
            if($this->tableMetadata->getColumnMetadata($name)->isGenerated()) {
                $row[$name] = $this->dbh->lastInsertId();
            }
        }
        return $row;
    }

    private function getRowWithNullPrimaryKeyAttributes() {
        $row=[];
        foreach ($this->tableMetadata->getPrimaryKeyAttribute() as $name) {
            $row[$name] = NULL;
        }
        return $row;
    }



}


