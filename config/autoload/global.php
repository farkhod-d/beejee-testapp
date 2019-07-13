<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

use Application\Delegator\TranslatorDelegator;
use Zend\Cache\Storage\Adapter\Filesystem;
use Zend\Mvc\I18n\Translator;
use Zend\Session\Storage\SessionArrayStorage;
use Zend\Session\Validator\HttpUserAgent;
use Zend\Session\Validator\RemoteAddr;

return [
    // Session configuration.
    'session_config' => [
        'name' => 'WPSID',
        // Указывает, как долго запоминать сеанс перед очисткой данных.
        'remember_me_seconds' => 60 * 60 * 1,
        // Session cookie will expire in 1 month.
        'cookie_lifetime' => 60 * 60 * 24 * 30,
        // How long to store session data on server (for 1 month).
        'gc_maxlifetime' => 60 * 60 * 24 * 30,
    ],
    // Session storage configuration.
    'session_storage' => [
        'type' => SessionArrayStorage::class
    ],
    // Session manager configuration.
    'session_manager' => [
        // Session validators (used for security).
        'validators' => [
            RemoteAddr::class,
            HttpUserAgent::class,
        ]
    ],

    'translator' => [
        'locale' => 'ru',
    ],
    'time_zone' => [
        'time_zone' => 'Europe/Moscow',
    ],

    // Cache configuration
    'caches' => [
        'FilesystemCache' => [
            'adapter' => [
                'name' => Filesystem::class,
                'options' => [
                    // Store cached data in this directory.
                    'cache_dir' => __DIR__ . '/../data/cache/',
                    // Store cached data for 1 hour.
                    'ttl' => 60 * 60 * 1
                ],
            ],
            'plugins' => [
                [
                    'name' => 'serializer',
                    'options' => [
                    ],
                ],
            ],
        ],
    ],

    'view_helper_config' => [
        'asset' => [
            'resource_map' => [
            ],
        ],
        'flashmessenger' => [
            'message_open_format' => '<div%s><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><ul class="mb-0 list-unstyled"><li>',
            'message_close_string' => '</li></ul></div>',
            'message_separator_string' => '</li><li>',
        ],
    ],
    'service_manager' => [
        'invokables' => [
        ],
        'factories' => [
        ],

        'aliases' => [
            'translator' => Translator::class,
        ],

        'delegators' => [
            Translator::class => [
                TranslatorDelegator::class,
            ],
        ],
    ],
];
