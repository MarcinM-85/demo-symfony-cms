<?php
namespace App\Bundle\FileGator\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class FileGatorExtension extends Extension
{
    public const ALIAS = "filegator";

    public function load(array $configs, ContainerBuilder $container): void
    {
        // scalanie konfiguracji
        $configuration = new Configuration(self::ALIAS);
        $processedConfig = $this->processConfiguration($configuration, $configs);

        // rejestracja jako parametrów kontenera
        $container->setParameter('filegator.config', $processedConfig);

        // ładowanie ewentualnych plików konfiguracyjnych z Resources/config
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        if (file_exists(__DIR__ . '/../../config/services.yaml')) {
            $loader->load('services.yaml');
        }
    }
    
    public function getAlias(): string {
        return self::ALIAS;
    }
}