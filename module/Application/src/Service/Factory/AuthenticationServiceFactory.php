<?php
declare(strict_types=1);

namespace Application\Service\Factory;

use Application\Service\AuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Zend\Session\SessionManager;

/**
 * Class AuthenticationServiceFactory
 * @package
 */
class AuthenticationServiceFactory implements FactoryInterface
{
    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $sessionManager = $container->get(SessionManager::class);
        $authStorage = new \Zend\Authentication\Storage\Session('Zend_Auth', 'session', $sessionManager);
        $authAdapter = $container->get(AuthAdapter::class);
        // Создаем сервис и внедряем зависимости в его конструктор.
        return new AuthenticationService($authStorage, $authAdapter);
    }
}
