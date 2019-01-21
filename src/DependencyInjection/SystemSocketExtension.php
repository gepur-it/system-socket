<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 18.01.19
 */

namespace GepurIt\SystemSocketBundle\DependencyInjection;

use GepurIt\SystemSocketBundle\Rabbit\StreamQueue;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class SystemSocketExtension
 * @package GepurIt\SystemSocketBundle\DependencyInjection
 */
class SystemSocketExtension extends Extension
{
    /**
     * Loads a specific configuration.
     *
     * @param array            $configs
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $configuration = $this->getConfiguration($configs, $container);
        $config        = $this->processConfiguration($configuration, $configs);

        $queue = $container->getDefinition(StreamQueue::class);
        $queue->replaceArgument(1, $config['queue_name']);
    }
}
