<?php

namespace App\Bundle\FileGator\Service\Config;

use League\Flysystem\Adapter\Local;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

class ConfigProvider
{
    private array $userConfig;

    public function __construct(ParameterBagInterface $params)
    {
        $this->userConfig = $params->get('filegator.config');
    }

    public function getAppEnv(){
        switch( $this->userConfig['env'] ){
            case 'dev':
                return 'development';
                break;
            case 'prod':
                return 'production';
                break;
            default:
                return $this->userConfig['env'];
                break;
        }
    }
    
    public function getConfig(): array 
    {
        $this->userConfig['services'] = $this->getFinalServices();
        return $this->userConfig;
    }
    
    public function getFinalServices(): array
    {
        $default = [
            'Filegator\Services\Logger\LoggerInterface' => [
                'handler' => '\Filegator\Services\Logger\Adapters\MonoLogger',
                'config' => [
                    'monolog_handlers' => [
                        function () {
                            return new StreamHandler(
                                $this->userConfig['path'].'private/logs/app.log',
                                Logger::DEBUG
                            );
                        },
                    ],
                ],
            ],
            'Filegator\Services\Session\SessionStorageInterface' => [
                'handler' => '\Filegator\Services\Session\Adapters\SessionStorage',
                'config' => [
                    'handler' => function () {
                        return new NativeSessionStorage([], new NativeFileSessionHandler(null));
                    },
                ],
            ],
            'Filegator\Services\Storage\Filesystem' => [
                'handler' => '\Filegator\Services\Storage\Filesystem',
                'config' => [
                    'separator' => '/',
                    'config' => [],
                    'adapter' => function () {
                        return new Local(
                            $this->userConfig['path'].'repository'
                        );
                    },
                ],
            ],
                            
            'Filegator\Services\Cors\Cors' => [
                'handler' => '\Filegator\Services\Cors\Cors',
                'config' => [
                    'enabled' => $this->userConfig['env'] == 'prod' ? false : true,
                ],
            ],
            'Filegator\Services\Tmpfs\TmpfsInterface' => [
                'handler' => '\Filegator\Services\Tmpfs\Adapters\Tmpfs',
                'config' => [
                    'path' => $this->userConfig['path'].'private/tmp/',
                    'gc_probability_perc' => 10,
                    'gc_older_than' => 60 * 60 * 24 * 2, // 2 days
                ],
            ],
            'Filegator\Services\Security\Security' => [
                'handler' => '\Filegator\Services\Security\Security',
                'config' => [
                    'csrf_protection' => true,
                    'csrf_key' => "123456", // randomize this
                    'ip_allowlist' => [],
                    'ip_denylist' => [],
                    'allow_insecure_overlays' => false,
                ],
            ],
            'Filegator\Services\View\ViewInterface' => [
                'handler' => '\Filegator\Services\View\Adapters\Vuejs',
                'config' => [
                    'add_to_head' => '',
                    'add_to_body' => '',
                ],
            ],
            'Filegator\Services\Archiver\ArchiverInterface' => [
                'handler' => '\Filegator\Services\Archiver\Adapters\ZipArchiver',
                'config' => [],
            ],
            'Filegator\Services\Auth\AuthInterface' => [
                'handler' => '\Filegator\Services\Auth\Adapters\JsonFile',
                'config' => [
                    'file' => $this->userConfig['path'].'/private/users.json',
                ],
            ],
            'Filegator\Services\Router\Router' => [
                'handler' => '\Filegator\Services\Router\Router',
                'config' => [
                    'query_param' => 'r',
                    'routes_file' => $this->userConfig['path'].'backend/Controllers/routes.php'
                ],
            ]
        ];

        return $this->overrideRecursiveDistinct($default, $this->userConfig['services']);
    }

    /*
     * Nadpisuje $base elementami tablicy $override. Jesli $override jest NULL, pozostawia domyslne ustawienia z $base
     */
    private function overrideRecursiveDistinct(array $base, array $override): array
    {
        foreach ($override as $key => $value) {
            if ( isset($base[$key]) && is_array($base[$key])) {
                if( is_array($value) )
                    $base[$key] = $this->overrideRecursiveDistinct( array_intersect_key($base[$key], $value) , $value );
                else if(is_null($value))
                    $base[$key] = $this->overrideRecursiveDistinct($base[$key], []);
            } else {
                if( !is_null($value) || !isset($base[$key]) )
                    $base[$key] = $value;
            }
        }

        return $base;
    }
}
