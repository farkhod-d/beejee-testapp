<?php
declare(strict_types=1);

use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\DBAL\Driver\PDOMySql\Driver as PDOMySqlDriver;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

// use Doctrine\DBAL\Driver\PDOPgSql\Driver as PDOPgSqlDriver;
return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => PDOMySqlDriver::class,
                'doctrine_type_mappings' => ['enum' => 'string'],
                'params' => [
                    'host' => 'localhost',
                    'port' => '3306',
                    'user' => '[]',
                    'password' => '[]',
                    'dbname' => '[]',
                    'charset' => 'utf8',
                    'driverOptions' => [
                        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES "UTF8"',
                    ],
                ],
            ],
        ],
        // We need to define now the 'orm_alternative' config,
        // the default one is already defined at the level of
        // doctrine module code
        'configuration' => [
            'orm_default' => [
                'metadata_cache' => 'array',
                'query_cache' => 'array',
                'result_cache' => 'array',
                'generate_proxies' => true,
                'proxy_dir' => 'data/DoctrineORMModule/Proxy',
                'proxy_namespace' => 'DoctrineORMModule/Proxy',
                'filters' => [],
                'driver' => 'orm_default',
            ],
        ],
        // настройка миграций
        'migrations_configuration' => [
            'orm_default' => [
                'directory' => 'data/DoctrineORMModule/Migrations',
            ],
        ],
        'driver' => [
            'orm_default' => [
                'drivers' => [
                     __NAMESPACE__ . '\Entity' => 'orm_default_driver',
                ],
            ],
            'orm_default_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../../module/Application/src/Entity',
                ],
            ],
        ],
    ],
];
