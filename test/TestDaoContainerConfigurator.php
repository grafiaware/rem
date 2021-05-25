<?php
namespace Test;

// kontejner
use Pes\Container\ContainerConfiguratorAbstract;
use Psr\Container\ContainerInterface;
// logger
use Pes\Logger\FileLogger;
// database
use Pes\Database\Handler\DbTypeEnum;
use Pes\Database\Handler\Account;
use Pes\Database\Handler\ConnectionInfo;
use Pes\Database\Handler\DsnProvider\DsnProviderMysql;
use Pes\Database\Handler\OptionsProvider\OptionsProviderMysql;
use Pes\Database\Handler\AttributesProvider\AttributesProvider;
use Pes\Database\Handler\Handler;
use Pes\Database\Handler\HandlerInterface;

use Pes\Database\Metadata\MetadataProviderMysql;


/**
 *
 *
 * @author pes2704
 */
class TestDaoContainerConfigurator extends ContainerConfiguratorAbstract {

    public function getAliases() {
        return [];
    }

    // Service vraci stale stejny objekt (vyrobi ho 1x)
    public function getServicesDefinitions() {
        return [
            // konfigurace
            'databaseType' => DbTypeEnum::MySQL,
            'databaseHost' => 'localhost',
            'databaseName' => 'tester_3_test',
            'databaseCharset' => 'utf8',
            'databaseCollation' => 'utf8_general_ci',

            'databaseUser' => 'root',
            'databasePassword' => 'spravce',

            'nameAdresarLogs' => 'Logs/Test/',   //??
            'nameFileSessionLog' => 'TestSession.log',
            'nameFileTesterApplicationLog' => 'Test.log',   //??

            Account::class => function(ContainerInterface $c) {
                return new Account($c->get('databaseUser'), $c->get('databasePassword'));
            },
            'databaseLogger' => function(ContainerInterface $c) {
                return FileLogger::getInstance($c->get('nameAdresarLogs'), $c->get('nameFileSessionLog'), FileLogger::REWRITE_LOG);
            },
            ConnectionInfo::class => function(ContainerInterface $c) {
                    return new ConnectionInfo(
                            $c->get('databaseType'),
                            $c->get('databaseHost'),
                            $c->get('databaseName'),
                            $c->get('databaseCharset'),
                            $c->get('databaseCollation')
                        );
            },
            DsnProviderMysql::class =>  function(ContainerInterface $c) {
                $dsnProvider = new DsnProviderMysql();
                $dsnProvider->setLogger($c->get('databaseLogger'));
                return $dsnProvider;
            },
            OptionsProviderMysql::class =>  function(ContainerInterface $c) {
                $optionsProvider = new OptionsProviderMysql();
                $optionsProvider->setLogger($c->get('databaseLogger'));
                return $optionsProvider;
            },
            AttributesProvider::class =>  function(ContainerInterface $c) {
                $attributesProvider = new AttributesProvider();
                $attributesProvider->setLogger($c->get('databaseLogger'));
                return $attributesProvider;
            },
            // database handler
            Handler::class => function(ContainerInterface $c) : HandlerInterface {
                return new Handler(
                        $c->get(Account::class),
                        $c->get(ConnectionInfo::class),
                        $c->get(DsnProviderMysql::class),
                        $c->get(OptionsProviderMysql::class),
                        $c->get(AttributesProvider::class),
                        $c->get('databaseLogger'));
            },
            MetadataProviderMysql::class => function(ContainerInterface $c) {
                return new MetadataProviderMysql($c->get(Handler::class));
            },

        ];
    }

    //objekty vzdycky se vyrobi znovu
    public function getFactoriesDefinitions() {
        return [];
    }
}
