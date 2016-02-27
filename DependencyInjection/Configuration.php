<?php

namespace Skillberto\ConsoleExtendBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('skillberto_console_extend');

        $node
            ->children()
                ->integerNode('default_chmod')
                    ->defaultValue(0777)
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}