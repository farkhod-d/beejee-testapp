<?php
/**
 * Local Configuration Override
 *
 * This configuration override file is for overriding environment-specific and
 * security-sensitive configuration information. Copy this file without the
 * .dist extension at the end and populate values as needed.
 *
 * @NOTE: This file is ignored from Git by default with the .gitignore included
 * in ZendSkeletonApplication. This is a good practice, as it prevents sensitive
 * credentials from accidentally being committed into version control.
 */

use Doctrine\DBAL\Driver\PDOMySql\Driver as PDOMySqlDriver;
use Doctrine\DBAL\Driver\PDOSqlite\Driver as PDOSqliteDriver;
use Doctrine\DBAL\Driver\PDOPgSql\Driver as PDOPgSqlDriver;

return [
    'doctrine' => [
        'connection' => [
            // 'orm_default2' => [
            //     'driverClass' => PDOMySqlDriver::class,
            //     'params' => [
            //         'host'     => 'localhost',
            //         'port' => '3306',
            //         'user'     => 'root',
            //         'password' => '',
            //         'dbname'   => 'test-beejee',
            //         'charset' => 'utf8',
            //     ]
            // ],
            // 'orm_default3' => [
            //     'driverClass' => PDOSqliteDriver::class,
            //     'params' => [
            //          'path'=> __DIR__ . '/../../data/db.sqlite',
            //     ]
            // ],
            'orm_default' => [
                'driverClass' => PDOPgSqlDriver::class,
                'params' => [
                    'host'      => 'ec2-54-217-234-157.eu-west-1.compute.amazonaws.com',
                    'dbname'    => 'dgdf1v7lcdlgr',
                    'user'      => 'cjuivmhwocfhpd',
                    'password'  => '9e16f1263ca247e0f43e1cbab347f19e5398562f44bd1557283e7ca08e3c4cf7',
                    'port'      => '5432',
                ]
            ],
        ],
    ],
];
