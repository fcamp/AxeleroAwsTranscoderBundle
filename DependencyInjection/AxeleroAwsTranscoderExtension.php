<?php

namespace Axelero\AwsTranscoderBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class AxeleroAwsTranscoderExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('axelero_aws_transcoder.pipeline_id', $config['pipeline_id']);
        $container->setParameter('axelero_aws_transcoder.key', $config['key']);
        $container->setParameter('axelero_aws_transcoder.region', $config['region']);
        $container->setParameter('axelero_aws_transcoder.secret', $config['secret']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }
}
