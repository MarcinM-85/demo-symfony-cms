<?php
namespace App\Bundle\SchebTwoFactorEmail\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class SchebTwoFactorEmailExtension extends Extension
{
    public const ALIAS = "scheb_2fa_email";

    public function load(array $configs, ContainerBuilder $container): void
    {
        // scalanie konfiguracji
        $configuration = new Configuration(self::ALIAS);
        $processedConfig = $this->processConfiguration($configuration, $configs);

        // rejestracja jako parametrów kontenera
        $container->setParameter('scheb_2fa_email', $processedConfig);

        // ładowanie ewentualnych plików konfiguracyjnych z Resources/config
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        if (file_exists(__DIR__ . '/../Resources/config/services.yaml')) {
            $loader->load('services.yaml');
        }

//        if (file_exists(realpath(__DIR__ . '/../Resources/config/routes.yaml'))) {
//            $loader->load('routes.yaml');
//        }
    }
    
    public function getAlias(): string {
        return self::ALIAS;
    }
}