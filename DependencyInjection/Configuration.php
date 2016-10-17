<?php

namespace Axelero\AwsTranscoderBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('axelero_aws_transcoder');

        $rootNode
            ->children()
                ->scalarNode('input_bucket')
                    ->isRequired()
            ->end()
                ->scalarNode('key')
                    ->isRequired()
            ->end()
                ->scalarNode('secret')
                    ->isRequired()
            ->end()
                ->scalarNode('region')
                    ->isRequired()
            ->end()
                ->scalarNode('output_bucket')
                    ->isRequired()
            ->end()
        ;

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
