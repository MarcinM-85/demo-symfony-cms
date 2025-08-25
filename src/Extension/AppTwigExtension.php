<?php
namespace App\Extension;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppTwigExtension extends AbstractExtension
{
    public function __construct(private RequestStack $requestStack, private UrlGeneratorInterface $urlGenerator, private Security $security) { }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_controller', [$this, 'isController']),
            new TwigFunction('default_admin_menu', [$this, 'getDefaultAdminMenu']),
        ];
    }

    public function isController(string $name): bool
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request) {
            return false;
        }

        $full = $request->attributes->get('_controller');
        if (!$full || !str_contains($full, '::')) {
            return false;
        }

        $controller = explode('::', $full)[0];

        return str_ends_with($controller, '\\' . $name) || str_ends_with($controller, '/' . $name);
    }

    public function getDefaultAdminMenu(): array
    {
        $menuData = [];

        $menuData[] = [
            'name' => 'News',
            'controller' => 'NewsController',
            'href' => $this->urlGenerator->generate('admin_news_list'),
        ];
        
        if( $this->security->isGranted('ROLE_SUPER_ADMIN') ){
            $menuData[] = [
                'name' => 'User',
                'controller' => 'UserController',
                'href' => $this->urlGenerator->generate('admin_user_list'),
            ];
        }

        return $menuData;
    }
}