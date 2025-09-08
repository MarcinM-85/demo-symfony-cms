<?php
namespace App\Bundle\FileGator\Controller;

use App\Bundle\FileGator\Model\UserInterface;
use App\Bundle\FileGator\Service\Config\ConfigProvider;
use App\Bundle\FileGator\Service\FileGatorApp;
use App\Bundle\FileGator\Service\FileGatorAppFactory;
use Filegator\Config\Config;
use Filegator\Container\Container;
use Filegator\Kernel\Request as FGRequest;
use Filegator\Kernel\Response as FGResponse;
use Filegator\Kernel\StreamedResponse as FGStreamedResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\Security\Core\User\UserInterface;

class FileGatorController extends AbstractController
{
    public function __construct(protected ParameterBagInterface $parameterBag) { }
    
    #[Route('/{path}', name: 'filegator', requirements: ['path' => '.*'])]
    public function proxy(Request $request, FileGatorAppFactory $factory, string $path = ''): Response
    {
        $this->denyAccessUnlessGranted( 'ROLE_ADMIN', NULL, 'Brak uprawnień do przeglądania zawartości');

        $session = $request->getSession();
        if (!$session->isStarted()) {
            $session->start();
        }

//        $configProvider = new ConfigProvider($this->parameterBag);
//        $configurationDataset = $configProvider->getConfig();
//
//        define('APP_ENV', $configProvider->getAppEnv());
//
//        $fgRequest = FGRequest::createFromGlobals();
//        $fgRequest->setSession($session);
//        
//        $fgContainer = new Container();
//        $fgContainer->set(UserInterface::class, $this->getUser());
//
//        // Zainicjalizuj FileGatora jako usługę
//        $fgApp = new FileGatorApp(
//            new Config($configurationDataset),
//            $fgRequest,
//            new FGResponse(),
//            new FGStreamedResponse(),
//            $fgContainer
//        );

        $fgRequest = FGRequest::createFromGlobals();
        $fgRequest->setSession($session);

        $fgApp = $factory->createApp($fgRequest, $this->getUser());
        
        return $fgApp->resolve('Filegator\Kernel\Response');
    }
}