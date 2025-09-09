<?php
// src/Front/Controller/NewsController.php

namespace App\Front\Controller;

use App\Entity\News;
use App\Enum\NewsCategoryEnum;
use App\Repository\NewsRepository;
use App\Service\MessageGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\EnumRequirement;
use Symfony\Component\Routing\Requirement\Requirement;

class NewsController extends AbstractController
{
    public function __construct(protected NewsRepository $repository, protected MessageGenerator $messageGenerator) {}

    #[Route('/z-kraju-i-ze-swiata', name: 'app_page_news_default')]
    #[Route('/z-kraju-i-ze-swiata/{enumSlug}/{page}', name: "app_page_news", defaults: ['page'=>1], requirements: ['enumSlug' => new EnumRequirement(NewsCategoryEnum::class), 'page'=>Requirement::DIGITS])]
    public function actionList( Request $request, ?NewsCategoryEnum $enumSlug = null): Response
    {
        if( is_null($enumSlug) ){
            return $this->redirectToRoute('app_page_news', [
                'enumSlug' => NewsCategoryEnum::Aktualnosci->value
            ]);
        }
        $eParams = [
            'sql' => [
                'select' => 'e',
                'order' => ['e.id' => 'ASC'],
                'where' => [
                    [
                        'field' => 'e.cat',
                        'operator' => '=',
                        'value' => $enumSlug->id()
                    ]
                ]
            ],
            'paginate' => true,
            'page' => $request->attributes->get('page'),
            'page_limit' => 1
        ];
        $newsElementList = $this->repository->getList($eParams);

//        dump( NewsCategoryEnum::Technologie->id() );
//        dump( $enumSlug->id() );
//        dump( $enumSlug->value );
        
//        dump($request->attributes);
//        dump($request->attributes->get('page'));
//        dump(NewsCategoryEnum::cases());

        return $this->render('front/news/list.html.twig', [
            'newsCategoryEnumCases'=> NewsCategoryEnum::cases(),
            'newsElementList' => $newsElementList,
            '_routeParams' => $request->attributes->get('_route_params'),
        ]);
    }

    #[Route('/z-kraju-i-ze-swiata/{enumSlug}/{id}-{entry}', name: "app_page_news_entry", defaults: [], requirements: ['enumSlug' => new EnumRequirement(NewsCategoryEnum::class), 'id' => Requirement::DIGITS, 'entry'=>Requirement::ASCII_SLUG])]
    public function actionEntry( Request $request, NewsCategoryEnum $enumSlug): Response
    {
        // Pobranie artykułu po slug + kategorii (enum)
        $newsEntry = $this->repository->findOneBy([
            'id' => $request->attributes->get('id'),
            'cat' => $enumSlug->id(),
        ]);

        if (!$newsEntry) {
            throw $this->createNotFoundException('Nie znaleziono artykułu.');
        }

        return $this->render('front/news/entry.html.twig', [
            'newsEntry' => $newsEntry,
            '_routeParams' => $request->attributes->get('_route_params'),
        ]);
    }
}