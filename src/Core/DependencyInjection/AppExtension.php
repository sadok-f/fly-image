<?php

namespace Core\DependencyInjection;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\AbstractExtension;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

class AppExtension extends AbstractExtension
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
            ->booleanNode('foo')->defaultTrue()->end()
            ->end();
    }

    public function loadExtension(array $config, ContainerConfigurator $containerConfigurator, ContainerBuilder $containerBuilder): void
    {
        if ($config['foo']) {
            $containerBuilder->register('foo_service', \stdClass::class);
        }
    }
}