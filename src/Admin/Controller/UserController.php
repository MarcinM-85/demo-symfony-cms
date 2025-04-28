<?php
// src/Admin/Controller/UserController.php

namespace App\Admin\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
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

    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
    }
}
