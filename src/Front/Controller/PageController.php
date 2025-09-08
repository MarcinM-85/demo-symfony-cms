<?php
// src/Front/Controller/NewsController.php

namespace App\Front\Controller;

use App\Service\MessageGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PageController extends AbstractController
{
    public function __construct(protected MessageGenerator $messageGenerator) {}

    #[Route('/', name: "app_page_home")]
    public function actionList( Request $request): Response
    {
        return $this->render('front/page/index.html.twig', [
            'config' => [
                'parameter_env_secret' => $this->getParameter('app.parameter_env_secret'),
                'parameter_env_encrypt_secret' => $this->getParameter('app.parameter_env_encrypt_secret')
            ],
            'controller_name' => $request->attributes->get('_controller'),
            'message_hash' => $this->messageGenerator->messageHash//'$messageGenerator->tmp//$messageGenerator->messageHash'
        ]);
    }
}