<?php
declare(strict_types=1);

namespace Application\Controller\Factory;

use Application\Controller\AuthController;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

/**
 * Class AuthControllerFactory
 * @package
 */
class AuthControllerFactory implements FactoryInterface
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
        return new AuthController(
            $container->get(\Application\Service\AuthManager::class)
        );
    }
}
