<?php
// src/Admin/Controller/UserController.php

namespace App\Admin\Controller;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormInterface;
use App\Admin\Controller\AbstractAdminController;
use App\Admin\Form\UserAddType;
use App\Admin\Form\UserEditType;
use App\Entity\User;
use App\Repository\UserRepository;

#[Route('/user', name: 'user')]
class UserController extends AbstractAdminController
{
    protected string $entityClass = User::class;
    protected string $formTypeAdd = UserAddType::class;
    protected string $formTypeEdit = UserEditType::class;
    protected string $templatePath = 'admin/user';
    protected string $routeBase = 'admin_user';

    public function __construct(UserRepository $repository, UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
        parent::__construct($repository);
    }

    protected function prePersistAdd( FormInterface $form ){
        $entity = $form->getData();

        $plainPassword = $form->get('plainPassword')->getData();
        $hashedPassword = $this->passwordHasher->hashPassword($entity, $plainPassword);
        $entity->setPassword($hashedPassword);
        $arrayRoles = $form->get('roles')->getData();
        $arrayRoles = is_array($arrayRoles) ? $arrayRoles : (array)$arrayRoles;
        $entity->setRoles( $arrayRoles );

        return [ $entity ];
    }

    protected function prePersistEdit( FormInterface $form ){
        $entity = $form->getData();

        $plainPassword = $form->get('plainPassword')->getData();
        if( !empty($plainPassword) ) {
            $hashedPassword = $this->passwordHasher->hashPassword($entity, $plainPassword);
            $entity->setPassword($hashedPassword);
        }
        $arrayRoles = $form->get('roles')->getData();
        $arrayRoles = is_array($arrayRoles) ? $arrayRoles : (array)$arrayRoles;
        $entity->setRoles( $arrayRoles );

        return [ $entity ];
    }

//    protected function preEditForm( int $entityId ) {
//        $entity = $this->repository->find($entityId);
//        
//        return $this->createForm($this->formTypeEdit, null, ['entityData'=>$entity]);
//    }
}
