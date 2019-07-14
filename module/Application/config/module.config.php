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
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'doctrine' => [
        'driver' => [
            'my_annotation_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/Entity'
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
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
            Controller\AuthController::class => Controller\Factory\AuthControllerFactory::class,
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
            ],
        ],
    ],

    // The 'access_filter' key is used by the User module to restrict or permit
    // access to certain controller actions for unauthorized visitors.
    'access_filter' => [
        'options' => [
            // The access filter can work in 'restrictive' (recommended) or 'permissive'
            // mode. In restrictive mode all controller actions must be explicitly listed
            // under the 'access_filter' config key, and access is denied to any not listed
            // action for not logged in users. In permissive mode, if an action is not listed
            // under the 'access_filter' key, access to it is permitted to anyone (even for
            // not logged in users. Restrictive mode is more secure and recommended to use.
            'mode' => 'restrictive'
        ],
        'controllers' => [
            Controller\IndexController::class => [
                // Give access to "index", "create" actions to anyone.
                ['actions' => ['index', 'create'], 'allow' => '*'],
                // Give access to "edit"  actions to authorized users only.
                ['actions' => ['edit'], 'allow' => '@']
            ],
            Controller\AuthController::class => [
                ['actions' => ['login', 'logout'], 'allow' => '*'],
            ],
        ]
    ],
    'service_manager' => [
        'aliases' => [
            IssueService::class => IssueServiceImpl::class
        ],
        'invokables' => [
            Service\AuthAdapter::class,
        ],
        'factories' => [
            IssueServiceImpl::class => IssueServiceFactory::class,
            Service\AuthManager::class => Service\Factory\AuthManagerFactory::class,
            \Zend\Authentication\AuthenticationService::class => Service\Factory\AuthenticationServiceFactory::class,
        ],
    ],
];
