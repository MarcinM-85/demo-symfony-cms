<?php
// src/Admin/Controller/NewsController.php

namespace App\Admin\Controller;

use App\Admin\Controller\AbstractAdminController;
use App\Admin\Form\NewsAddType;
use App\Admin\Form\NewsEditType;
use App\Entity\News;
use App\Repository\NewsRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/news', name: 'news')]
class NewsController extends AbstractAdminController
{
    protected string $entityClass = News::class;
    protected string $formTypeAdd = NewsAddType::class;
    protected string $formTypeEdit = NewsEditType::class;
    protected string $templatePath = 'admin/news';
    protected string $routeBase = 'admin_news';

    public function __construct(NewsRepository $repository)
    {
        parent::__construct($repository);
    }

    protected function prePersistAdd( FormInterface $form ){
        $entity = $form->getData();

        $entity->setDescription("");

        return [ $entity ];
    }
    
    #[Route('/cos', name: '_cos', methods: ['GET'])]
    public function cos(): Response
    {
        //$this->fetchList($em);
        $this->twigParams['routes']['_self'] = $this->generateUrl($this->routeBase.'_cos');
        return $this->render("{$this->templatePath}/list.html.twig", $this->twigParams);
    }
}
