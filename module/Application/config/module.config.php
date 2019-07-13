<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Service\Factory\IssueServiceFactory;
use Application\Service\Impl\IssueServiceImpl;
use Application\Service\IssueService;
use Zend\Mvc\Controller\LazyControllerAbstractFactory;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\EntityManager;

return [

    'doctrine' => [
        'driver' => [
           'my_annotation_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__ .  '/../src/Entity'
                ],
            ],
            'orm_default' => [
                'drivers' => [
                     __NAMESPACE__ . '\Entity' => 'orm_default_driver'
                ],
            ],
        ],
    ],

    // router in router.config.php
    'controllers' => [
        'invokables' => [
            //Controller\IndexController::class
        ],
        'factories' => [
            Controller\IssueController::class => Controller\Factory\IssueControllerFactory::class,
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
            // Controller\YandexController::class => Controller\Factory\YandexControllerFactory::class,
            // Controller\BackController::class => LazyControllerAbstractFactory::class,
        ],
    ],

    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'layout' => 'layout/layout',
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            'application' => __DIR__ . '/../view',
        ],

        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
    'view_helper_config' => [
        'asset' => [
            'resource_map' => [
                // 'fonts.css' => 'https://fonts.googleapis.com/css?family=Open+Sans&subset=cyrillic',
                // 'payment.style.css' => '/assets/css/payment.style.min.css',
            ],
        ],
    ],

    'service_manager' => [
        'aliases' => [
            // PaymentManagerInterface::class => PaymentManager::class,
            // YandexServiceInterface::class => YandexService::class,
            // WebmoneyServiceInterface::class => WebmoneyService::class,
            IssueService::class => IssueServiceImpl::class
        ],

        'invokables' => [
            // ZendClient::class,
        ],
        'factories' => [
            IssueServiceImpl::class => IssueServiceFactory::class
            // YandexClient::class => YandexClientFactory::class,

            // PaymentManager::class => PaymentManagerFactory::class,
            // YandexService::class => YandexServiceFactory::class,
            // WebmoneyService::class => WebmoneyServiceFactory::class,

        ],
    ],
];
