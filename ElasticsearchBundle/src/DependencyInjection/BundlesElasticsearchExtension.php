<?php

namespace Bundles\ElasticsearchBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class BundlesElasticsearchExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);


        $container->setParameter('bundles_elasticsearch.host', $config['host']);
        $container->setParameter('bundles_elasticsearch.index', $config['index']);
        $container->setParameter('bundles_elasticsearch.username', $config['username']);
        $container->setParameter('bundles_elasticsearch.password', $config['password']);


        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.yaml');
    }
}
