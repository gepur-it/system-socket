<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 21.01.19
 */

namespace GepurIt\SystemSocketBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package GepurIt\SystemSocketBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('system_socket');

        $rootNode
            ->children()
                ->scalarNode('queue_name')
                    ->cannotBeEmpty()
                    ->defaultValue('erp_to_socket_appeal')
                ->end()
            ->end()
        ;
        return $treeBuilder;
    }
}
