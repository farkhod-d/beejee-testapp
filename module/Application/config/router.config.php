<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'create' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/create/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'create',
                    ],
                ],
            ],
            'login' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/login',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'login',
                    ],
                ],
            ],
            'logout' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/logout',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'logout',
                    ],
                ],
            ],

            // 'application' => [
            //     'type'    => Segment::class,
            //     'options' => [
            //         'route'    => '/application[/:action]',
            //         'defaults' => [
            //             'controller' => Controller\IndexController::class,
            //             'action'     => 'index',
            //         ],
            //     ],
            // ],
            'issue' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/api/issue[/:id]',
                    'constraints' => [
                        'id'     => '[a-zA-Z0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\IssueController::class,
                        'isAuthorizationRequired' => false,
                    ],
                ],
            ],
        ],
    ],
];
