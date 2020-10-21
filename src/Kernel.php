<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import('../config/{packages}/*.php');
        $container->import('../config/{packages}/' . $this->environment . '/*.php');

        if (\is_file($path = \dirname(__DIR__) . '/config/services.php')) {
            (require $path)($container->withPath($path), $this);
        }
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import('../config/{routes}/' . $this->environment . '/*.php');
        $routes->import('../config/{routes}/*.php');

        if (\is_file($path = \dirname(__DIR__) . '/config/routes.php')) {
            (require $path)($routes->withPath($path), $this);
        }
    }

    protected function dumpContainer(ConfigCache $cache, ContainerBuilder $container, string $class, string $baseClass)
    {
        // cache the container
        $dumper = new PhpDumper($container);

        if (class_exists('ProxyManager\Configuration') && class_exists('Symfony\Bridge\ProxyManager\LazyProxy\PhpDumper\ProxyDumper')) {
            $dumper->setProxyDumper(new ProxyDumper());
        }

        // FrameworkBundle triggers Segment Fault
        $classes = array_map('get_class', $this->bundles);
        unset($classes['FrameworkBundle']);

        $content = $dumper->dump([
            'class' => $class,
            'base_class' => $baseClass,
            'file' => $cache->getPath(),
            'as_files' => true,
            'debug' => $this->debug,
            'build_time' => $container->hasParameter('kernel.container_build_time') ? $container->getParameter('kernel.container_build_time') : time(),
            'preload_classes' => $classes,
        ]);

        $rootCode = array_pop($content);
        $dir = \dirname($cache->getPath()).'/';
        $fs = new Filesystem();

        foreach ($content as $file => $code) {
            $fs->dumpFile($dir.$file, $code);
            @chmod($dir.$file, 0666 & ~umask());
        }
        $legacyFile = \dirname($dir.key($content)).'.legacy';
        if (file_exists($legacyFile)) {
            @unlink($legacyFile);
        }

        $cache->write($rootCode, $container->getResources());
    }
}
