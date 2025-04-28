<?php
// src/Controller/NewsController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Routing\Requirement\EnumRequirement;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\News;
use App\Enum\NewsCategoryEnum;
use App\Repository\NewsRepository;
//use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
//use App\Service\MessageGenerator;

class NewsController extends AbstractController
{
    public function __construct(protected NewsRepository $repository) {}

    #[Route('/z-kraju-i-ze-swiata/{enumSlug}/{page}', name: "app_page_informacje", defaults: ['page'=>1], requirements: ['enumSlug' => new EnumRequirement(NewsCategoryEnum::class), 'page'=>Requirement::DIGITS])]
    public function actionList( Request $request, NewsCategoryEnum $enumSlug = NewsCategoryEnum::Aktualnosci): Response
    {
//        var_dump( NewsCategoryEnum::Technologie->id() );
//        var_dump( $enumSlug->id() );
//        var_dump( $enumSlug->value );
        
//        var_dump($request->attributes);
//        var_dump($request->attributes->get('page'));

        return $this->render('front/news.html.twig', [
            'config' => [
                'parameter_env_secret' => $this->getParameter('app.parameter_env_secret'),
                'parameter_env_encrypt_secret' => $this->getParameter('app.parameter_env_encrypt_secret')
            ],
            'luckyNumber' => 1,
            'range' => [0, 100],
            'controller_name' => $request->attributes->get('_controller'),
            'message_hash' => '$messageGenerator->tmp//$messageGenerator->messageHash'
        ]);
    }

    #[Route('/z-kraju-i-ze-swiata/{enumSlug}/{entry}', name: "app_page_informacje_entry", defaults: [], requirements: ['enumSlug' => new EnumRequirement(NewsCategoryEnum::class), 'entry'=>Requirement::ASCII_SLUG])]
    public function actionEntry( Request $request, NewsCategoryEnum $enumSlug): Response
    {
        var_dump( $enumSlug );
        echo 'ENTRY';

        return $this->render('front/page.html.twig', [
            'config' => [
                'parameter_env_secret' => $this->getParameter('app.parameter_env_secret'),
                'parameter_env_encrypt_secret' => $this->getParameter('app.parameter_env_encrypt_secret')
            ],
            'luckyNumber' => 1,
            'range' => [0, 100],
            'controller_name' => $request->attributes->get('_controller'),
            'message_hash' => '$messageGenerator->tmp//$messageGenerator->messageHash'
        ]);
    }
}