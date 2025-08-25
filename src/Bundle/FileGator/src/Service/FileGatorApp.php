<?php

/*
 * To jest kopia Filegator/App.php pozbawiona $response->send() w konstruktorze.
 */

namespace App\Bundle\FileGator\Service;

use Filegator\Config\Config;
use Filegator\Container\Container;
use Filegator\Kernel\Request;
use Filegator\Kernel\Response;
use Filegator\Kernel\StreamedResponse;

class FileGatorApp
{
    private $container;

    public function __construct(Config $config, Request $request, Response $response, StreamedResponse $sresponse, Container $container)
    {
        $container->set(Config::class, $config);
        $container->set(Container::class, $container);
        $container->set(Request::class, $request);
        $container->set(Response::class, $response);
        $container->set(StreamedResponse::class, $sresponse);

        foreach ($config->get('services', []) as $key => $service) {
            $container->set($key, $container->get($service['handler']));
            $container->get($key)->init(isset($service['config']) ? $service['config'] : []);
        }

        $this->container = $container;
    }

    public function resolve($name)
    {
        return $this->container->get($name);
    }
}
