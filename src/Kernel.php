<?php

declare(strict_types=1);

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use function dirname;

final class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    /**
     * {@inheritDoc}
     */
    protected function configureContainer(ContainerConfigurator $container): void
    {
        /**
         * @var  string $environment
         */
        $environment = $this->environment;
        $container->import('../config/{packages}/*.yaml');
        $container->import('../config/{packages}/' . $environment . '/*.yaml');

        if (is_file(dirname(__DIR__) . '/config/services.yaml')) {
            $container->import('../config/services.yaml');
            $container->import('../config/{services}_' . $environment . '.yaml');
        } elseif (is_file($path = dirname(__DIR__) . '/config/services.php')) {
            /** @noinspection PhpIncludeInspection */
            (require $path)($container->withPath($path), $this);
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        /**
         * @var  string $environment
         */
        $environment = $this->environment;
        $routes->import('../config/{routes}/' . $environment . '/*.yaml');
        $routes->import('../config/{routes}/*.yaml');

        if (is_file(dirname(__DIR__) . '/config/routes.yaml')) {
            $routes->import('../config/routes.yaml');
        } elseif (is_file($path = dirname(__DIR__) . '/config/routes.php')) {
            /** @noinspection PhpIncludeInspection */
            (require $path)($routes->withPath($path), $this);
        }
    }
}
