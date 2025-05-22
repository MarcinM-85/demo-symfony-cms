<?php
// src/Admin/Controller/AuthController.php

namespace App\Admin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\RedirectResponse;
//use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    #[Route( '/', name: 'auth')]
    public function index(AuthenticationUtils $authenticationUtils, AuthorizationCheckerInterface $authChecker ): Response
    {
        if ($authChecker->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin_news_list');
        }

        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('admin/auth/index.html.twig', [
                'controller_name'   => 'AuthController',
                'error'             => $error
        ]);
    }

//    #[Route( '/2fa', name: '2fa')]
//    public function twoFactor(AuthenticationUtils $authenticationUtils, AuthorizationCheckerInterface $authChecker): Response
//    {
//        dump($authChecker->isGranted('AUTHENTICATION_IN_PROGRESS'));
//        dump($authChecker);
////        if ($authChecker->isGranted('ROLE_ADMIN')) {
////            return $this->redirectToRoute('admin_news_list');
////        }
////        if (!$authChecker->isGranted('AUTHENTICATION_ACCESS')) {
////            return $this->redirectToRoute('admin_auth');
////        }
//
//        $error = $authenticationUtils->getLastAuthenticationError();
//
//        return $this->render('admin/auth/2fa.html.twig', [
//                'controller_name'   => 'AuthController',
//                'error'             => $error
//        ]);
//    }

    #[Route( '/logout', name: 'auth_logout')]
    public function logout(): void
    {
        //Tu sie nic nie dzieje. Chodzi o ustawienie Route: admin/logout
    }
}
