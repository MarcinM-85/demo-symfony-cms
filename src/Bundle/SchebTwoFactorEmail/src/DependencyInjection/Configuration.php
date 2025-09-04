<?php
namespace App\Bundle\SchebTwoFactorEmail\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function __construct(private string $alias){}

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder($this->alias);

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('email')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('sender_email')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('sender_name')
                            ->defaultValue('System')
                        ->end()
                        ->scalarNode('subject')
                            ->defaultValue('Authorization Code')
                        ->end()
                        ->scalarNode('template')
                            ->defaultNull()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('code_generator')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->integerNode('digits')
                            ->defaultValue(3)
                        ->end()
                        ->scalarNode('expires_after')
                            ->defaultValue('PT5M') //ISO8601 Duration Time. Default: 5 minut
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('form_renderer')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('template')
                            ->defaultNull()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}