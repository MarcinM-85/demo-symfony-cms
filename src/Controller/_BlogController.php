<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

#[Route('/{_locale}/blog', name: 'app_blog_', requirements: ['_locale' => 'pl|en|es|fr'], defaults: ['_locale' => 'pl'])]
class _BlogController extends AbstractController
{
    #[Route('/{page}', name: 'list', requirements: ['page' => Requirement::DIGITS], defaults: ['page' => 1])]
    public function showList(Request $request): Response
    {
        try {
            $url = $this->generateUrl('app_blog_entry', ['slug' => 'artykul-blogowy'], UrlGeneratorInterface::ABSOLUTE_URL);
            return new Response('To jest lista Artykułów, Locale: ' . implode(',', $this->getParameter('app.supported_locales')) . ', Strona: ' . $request->attributes->get('page'). ', URL-Entry: ' . $url);
        } catch (RouteNotFoundException $e) {
             return new Response('NIE MA TAKIEJ ŚCIEZKI');
        }
    }

    #[Route('/json', name: 'json', methods: ['GET', 'HEAD'])]
    public function showJson(Request $request): Response
    {
        $response = $this->forward('App\Controller\PageController::lucky', [
            'min'  => 1,
            'getMax' => 120
        ]);
        return $response;
        //return $this->json(['username'=>['Adam', 'Filip']]);
    }

    #[Route('/{slug}', name: 'entry', methods: ['GET', 'HEAD'])]
    public function showEntry(Request $request): Response
    {
        $url = $this->generateUrl('app_blog_list', ['cat' => 12], UrlGeneratorInterface::ABSOLUTE_URL);
        return new Response('To jest pjedynczy Artykuł o URL: ' . $request->attributes->get('slug') . ', Locale: ' . $request->attributes->get('_locale') . ', URL-List: ' . $url);
    }

//#[Route('/blog/{min}', condition: "params['min'] <= 2")]
//class BlogController extends AbstractController
//{
//    public function __invoke( Request $request, MessageGenerator $messageGenerator, int $min = 0, #[MapQueryParameter('max')] int $getMax=100): Response
//    {
//        $number = random_int($min, $getMax);
//        if($number%2==0) {
////            throw $this->createNotFoundException('Osiągnięto MAX=2');
//        }
//        
//        return $this->render('page.html.twig', [
//            'config' => [
//                'parameter_env_secret' => $this->getParameter('app.parameter_env_secret'),
//                'parameter_env_encrypt_secret' => $this->getParameter('app.parameter_env_encrypt_secret')
//            ],
//            'luckyNumber' => $number,
//            'range' => [$min, $getMax],
//            'controller_name' => $request->attributes->get('_controller'),
//            'message_hash' => $messageGenerator->tmp//$messageGenerator->messageHash
//        ]);
//    }
//}
}