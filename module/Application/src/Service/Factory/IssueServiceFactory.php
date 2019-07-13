<?php
declare(strict_types=1);

namespace Application\Service\Factory;

use Application\Service\Impl\IssueServiceImpl;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class IssueServiceFactory
 * @package
 */
class IssueServiceFactory implements FactoryInterface
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
        $em = $container->get(EntityManager::class);
        return new IssueServiceImpl($em);
    }
}
