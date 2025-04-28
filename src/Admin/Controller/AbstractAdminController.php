<?php
// src/Admin/Controller/AbstractAdminController.php

namespace App\Admin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AbstractEntityRepository;

abstract class AbstractAdminController extends AbstractController
{
    protected string $entityClass;
    protected string $formTypeAdd;
    protected string $formTypeEdit;
    protected string $templatePath;
    protected string $routeBase;
    protected array $twigParams = [];

    public function __construct(protected AbstractEntityRepository $repository) { }

//    public function fetchList():AbstractAdminController {
//        $eParams = [
//            'select' => 'e',
//            'order' => ['e.id' => 'ASC'],
//            'limit' => 20,
//            'offset' => 0,
//            'paginate' => false
//        ];
//
//        $this->twigParams[ 'elementList' ] = $this->em->getRepository($this->entityClass)->getList($eParams);
//
//        return $this;
//    }

    #[Route('/{page}', name: '_list', methods: ['GET'], requirements: ['page' => Requirement::DIGITS], defaults: ['page' => 1])]
    public function list(Request $request): Response
    {
//        $this->addFlash('success', 'Wiadomość w list');
        $eParams = [
            'sql' => [
                'select' => 'e',
                'order' => ['e.id' => 'ASC']
            ],
            'paginate' => true,
            'page' => $request->attributes->get('page'),
            'page_limit' => 20
        ];
        $this->twigParams[ 'elementList' ] = $this->repository->getList($eParams);

        return $this->render("{$this->templatePath}/list.html.twig", $this->twigParams);
    }

    #[Route('/add', name: '_add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $entity = new $this->entityClass();
        $form = $this->createForm($this->formTypeAdd, $entity);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();
            //$this->prePersist();
                                                        $plainPassword = $form->get('plainPassword')->getData();
                                                        $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);
                                                        $entity->setPassword($hashedPassword);
                                                        $arrayRoles = $form->get('roles')->getData();
                                                        $arrayRoles = is_array($arrayRoles) ? $arrayRoles : (array)$arrayRoles;
                                                        $entity->setRoles( $arrayRoles );
            $em->persist($entity);
            $em->flush();
            $this->addFlash('success', 'Dodano');
            return $this->redirectToRoute($this->routeBase.'_list');
        }

        return $this->render($this->templatePath . '/add-form.html.twig', [
            'form' => $form//->createView()
        ]);
    }

    #[Route('/edit/{id}', name: '_edit')]
    public function edit(Request $request, EntityManagerInterface $em, int $id): Response
    {
        $entity = $this->repository->find($id);
        $form = $this->createForm($this->formTypeEdit, $entity);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();
            //$this->prePersist();
                                                        $plainPassword = $form->get('plainPassword')->getData();
                                                        if( !empty($plainPassword) ) {
                                                            $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);
                                                            $entity->setPassword($hashedPassword);
                                                        }
                                                        $arrayRoles = $form->get('roles')->getData();
                                                        $arrayRoles = is_array($arrayRoles) ? $arrayRoles : (array)$arrayRoles;
                                                        $entity->setRoles( $arrayRoles );
            $em->persist($entity);
            $em->flush();
            //$this->addFlash('success', 'Edycja udana');
            return $this->redirectToRoute($this->routeBase.'_list');
        }

        return $this->render($this->templatePath . '/edit-form.html.twig', [
            'form' => $form//->createView()
        ]);
    }

    #[Route('/delete/{id}', name: '_delete', methods: ['POST'])]
    public function delete(EntityManagerInterface $em, int $id): Response
    {
        $entity = $this->repository->find($id);
        if (!$entity) {
            throw $this->createNotFoundException();
        }
        
        if ($entity) {
//            $em->remove($entity);
//            $em->flush();
            $this->addFlash('success', 'Usunięto');
        }
        return $this->redirectToRoute($this->routeBase.'_list');
    }
}
