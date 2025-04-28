<?php
// src/Admin/Controller/NewsController.php

namespace App\Admin\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Admin\Controller\AbstractAdminController;
use App\Entity\News;
use App\Repository\NewsRepository;

#[Route('/news', name: 'news')]
class NewsController extends AbstractAdminController
{
    protected string $entityClass = News::class;
    protected string $formTypeAdd = '';
    protected string $templatePath = 'admin/news';
    protected string $routeBase = 'admin_news';

    public function __construct(NewsRepository $repository)
    {
        parent::__construct($repository);
    }

    #[Route('/cos', name: '_cos', methods: ['GET'])]
    public function cos(): Response
    {
        //$this->fetchList($em);
        $this->twigParams['routes']['_self'] = $this->generateUrl($this->routeBase.'_cos');
        return $this->render("{$this->templatePath}/list.html.twig", $this->twigParams);
    }
}
