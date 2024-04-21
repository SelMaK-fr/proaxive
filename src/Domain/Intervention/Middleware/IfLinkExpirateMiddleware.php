<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Domain\Intervention\Middleware;

use Envms\FluentPDO\Query;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\App;
use Slim\Interfaces\RouteParserInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Routing\RouteContext;

class IfLinkExpirateMiddleware implements MiddlewareInterface
{

    public function __construct(
        private readonly Query $query,
        private readonly RouteParserInterface $routeParser,
        private readonly SessionInterface $session,
        private readonly App $app
    ){}
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $int = $this->app->getContainer()->get('parameters')->expiration_link;
        if(!isset($_COOKIE['proaxive2-auth']) && !isset($_SESSION['customer']) && $int > 0){
            $routeContext = RouteContext::fromRequest($request);
            $route = $routeContext->getRoute();
            $i = $this->query->from('interventions')
                ->select('id, ref_for_link')
                ->where('interventions.ref_for_link = ?', [$route->getArgument('ref_for_link')])
                ->fetch()
            ;
            $dateIntervention = date_create_immutable($i['created_at']);
            $interval = new \DateInterval("P{$int}D");
            $newDate = $dateIntervention->add($interval);
            $today = date_create_immutable();
            // Verify link time
            if($newDate < $today){
                $response = new ResponseFactory();
                $this->session->getFlash()->add('error', "[Lien expiré] Cette intervention n'est plus consultable en mode public.");
                return $response->createResponse()->withStatus(302)->withHeader('Location', $this->routeParser->urlFor('app_home'));
            }
        }
        return $handler->handle($request);
    }
}