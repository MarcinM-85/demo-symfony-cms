<?php

/*
 * To jest kopia Filegator/App.php pozbawiona $response->send() w konstruktorze.
 */

namespace App\Bundle\FileGator\Service;

use App\Bundle\FileGator\Model\UserInterface;
use App\Bundle\FileGator\Service\Config\ConfigProvider;
use App\Bundle\FileGator\Service\FileGatorApp;
use Filegator\Config\Config;
use Filegator\Container\Container;
use Filegator\Kernel\Request as FGRequest;
use Filegator\Kernel\Response as FGResponse;
use Filegator\Kernel\StreamedResponse as FGStreamedResponse;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FileGatorAppFactory
{
    private Config $config;
    private FGResponse $response;
    private FGStreamedResponse $streamedResponse;
    private ParameterBagInterface $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;

        $configProvider = new ConfigProvider($this->parameterBag);
        $this->config = new Config($configProvider->getConfig());

        $this->response = new FGResponse();
        $this->streamedResponse = new FGStreamedResponse();
        
        if (!defined('APP_ENV')) {
            define('APP_ENV', $configProvider->getAppEnv());
        }
    }

    public function createApp(FGRequest $request, UserInterface $user): FileGatorApp
    {
        $container = new Container();
        $container->set(UserInterface::class, $user);

        return new FileGatorApp(
            $this->config,
            $request,
            $this->response,
            $this->streamedResponse,
            $container
        );
    }
}
