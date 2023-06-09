<?php declare(strict_types=1);

namespace Gponty\IdLoomBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class IdLoomBundle extends AbstractBundle
{
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.yml');

        $container->services()
            ->get(IdLoomApi::class)
            ->arg('$idLoomUrl', $config['idloom_url'])
        ;
    }

    public function configure(DefinitionConfigurator $definition): void
    {
        // if the configuration is short, consider adding it in this class
        $definition->rootNode()
            ->children()
            ->scalarNode('idloom_url')->defaultValue('%env(IDLOOM_URL)%')->end()
            ->end()
        ;
    }
}
