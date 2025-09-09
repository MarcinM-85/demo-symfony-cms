<?php
// src/Front/Controller/NewsController.php

namespace App\Front\Controller;

use App\Repository\NewsRepository;
use App\Service\MessageGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PageController extends AbstractController
{
    public function __construct(protected MessageGenerator $messageGenerator) {}

    #[Route('/', name: "app_page_home")]
    public function actionList( Request $request, NewsRepository $newsRepository): Response
    {
        $eParams = [
            'sql' => [
                'select' => 'e',
                'order' => ['e.id' => 'ASC']
            ],
            'paginate' => true,
            'page' => $request->attributes->get('page'),
            'page_limit' => 2
        ];
        $newsElementList = $newsRepository->getList($eParams);
        
        return $this->render('front/page/index.html.twig', [
            'latestNewsElementList' => $newsElementList,
            'config' => [
                'parameter_env_secret' => $this->getParameter('app.parameter_env_secret'),
                'parameter_env_encrypt_secret' => $this->getParameter('app.parameter_env_encrypt_secret')
            ],
            'controller_name' => $request->attributes->get('_controller'),
            'message_hash' => $this->messageGenerator->messageHash//'$messageGenerator->tmp//$messageGenerator->messageHash'
        ]);
    }
}