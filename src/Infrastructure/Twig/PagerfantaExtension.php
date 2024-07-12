<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Infrastructure\Twig;

use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap4View;
use Slim\Interfaces\RouteParserInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PagerfantaExtension extends AbstractExtension
{

    public function __construct(private readonly RouteParserInterface $routeParser){}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('pagerfanta', [$this, 'getPagerfanta'], ['is_safe' => ['html']]),
        ];
    }

    public function getPagerfanta(Pagerfanta $paginatedResults, string $route, array $args = []): string
    {
        $view = new TwitterBootstrap4View();
        return $view->render($paginatedResults, function (int $page) use ($route, $args) {
            if($page > 1) {
                $args['p'] = $page;
            }
            return $this->routeParser->urlFor($route, [], $args);
        });
    }
}