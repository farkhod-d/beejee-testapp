<?php
declare(strict_types=1);

namespace Application\Service\Factory;

use Application\Service\AuthManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;

/**
 * Class AuthManagerFactory
 * @package
 */
class AuthManagerFactory implements FactoryInterface
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
        $authenticationService = $container->get(\Zend\Authentication\AuthenticationService::class);
        $sessionManager = $container->get(SessionManager::class);

        $config = $container->get('Config');
        if (isset($config['access_filter'])) {
            $config = $config['access_filter'];

        } else {
            $config = [];
        }

        return new AuthManager(
            $authenticationService,
            $sessionManager,
            $config
        );
    }
}
