<?php
declare(strict_types=1);
namespace App\Twig;

use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

class AppRoutingExtension extends \Twig_Extension
{
    /**
     * @var Router
     */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function getFunctions()
    {
        return [
            new \Twig_Function('getRoutes', [$this, 'filterRoutes'])
        ];
    }

    /**
     * @return array
     */
    public function filterRoutes()
    {
        $routes = $this->getAllRoutes();
        foreach ($routes as $key => $route) {
            if (substr($key, 0, 1) === "_") {
                unset($routes[$key]);
            }
        }

        return array_keys($routes);
    }

    /**
     * @return array
     */
    private function getAllRoutes(): array
    {
        return $this->router->getRouteCollection()->all();
    }

}