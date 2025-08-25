<?php

namespace App\Front\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ErrorController extends AbstractController
{
    #[Route('/error', name: 'app_error')]
    public function show(Request $request, FlattenException $exception): Response
    {
        $statusCode = $exception->getStatusCode();
        return $this->render('error/error.html.twig', [
            'code' => $statusCode,
            'message' => $this->getErrorMessage($statusCode),
            'request_uri' => $request->getRequestUri()
        ]);
    }

    private function getErrorMessage(?int $code): string
    {
        // Możesz zdefiniować własne wiadomości dla różnych kodów błędów
        $messages = [
            404 => 'Page not found',
            500 => 'Internal server error',
            // Dodaj inne kody błędów, jeśli chcesz
        ];

        return $messages[$code] ?? 'An unrecognized error occurred';
    }
}
