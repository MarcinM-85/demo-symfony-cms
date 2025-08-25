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
            ->info('Configuration constants for FileGatora configuration.php file')
            ->children()
                ->scalarNode("env")
                    ->defaultValue("%kernel.environment%")
                    ->info("")
                ->end()
            ->end();

        return $treeBuilder;
    }
}