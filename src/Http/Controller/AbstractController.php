<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Controller;

use Awurth\Validator\StatefulValidator;
use Creitive\Breadcrumbs\Breadcrumbs;
use Envms\FluentPDO\Query;
use Laminas\Diactoros\Response\JsonResponse;
use Odan\Session\SessionInterface;
use Palmtree\Form\Form;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Selmak\Proaxive2\Domain\Auth\SessionUser;
use Selmak\Proaxive2\Infrastructure\Parameter\Interface\ParameterInterface;
use Selmak\Proaxive2\Settings\SettingsInterface;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

abstract class AbstractController
{

    public function __construct(
        protected readonly SettingsInterface $settings,
        protected readonly Twig $twig,
        protected readonly Query $query,
        protected readonly RouteParserInterface $routeParser,
        protected readonly SessionInterface $session,
        protected readonly LoggerInterface $logger,
        protected readonly Breadcrumbs $breadcrumbs,
        protected readonly ParameterInterface $parameter,
        protected readonly StatefulValidator $validator
    ){
    }

    /**
     * @param string $key
     * @param string $value
     * @return array|bool|string
     */
    protected function getParameter(string $key, string $value): array|bool|string
    {
        return $this->settings->get($key)[$value];
    }

    /**
     * @param string $key
     * @return array|bool|string
     */
    protected function getParameters(string $key): array|bool|string
    {
        return $this->settings->get($key);
    }

    /**
     * @param Response $response
     * @param string $template
     * @param array|null $data
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    protected function render(Response $response, string $template, ?array $data = []): Response
    {
        $response = $response->withHeader('Content-Type', 'text/html; charset=utf-8');
        return $this->twig->render($response, $template, $data);
    }

    /**
     * @param string $template
     * @param array|null $data
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    protected function view(string $template, ?array $data = []): string
    {
        return $this->twig->fetch($template, $data);
    }

    /**
     * Return the table data's in the controller. $classname => your repository class in /app/Repository
     * @param string $classname
     * @return mixed
     */
    protected function getRepository(string $classname): mixed
    {
        try {
            return new $classname($this->query);
        } catch (\Exception $e){
            return new \Exception(sprintf('Repository empty : %s', $e->getMessage()));
        }
    }
    /**
     * @param string $type
     * @param mixed|null $data
     * @param array|null $args
     * @return Form
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function createForm(string $type, mixed $data = null, mixed $args = []): Form
    {
        return (new $type($this->query))->createFormBuilder($data, $args);
    }

    /**
     * @param string $route
     * @return string
     */
    protected function generateUrl(string $route): string
    {
        return $this->getUrlFor($route);
    }

    /**
     * @param string $routeName
     * @param array|null $data
     * @param array|null $params
     * @return Response
     */
    protected function redirectToRoute(string $routeName, ?array $data = [], ?array $params = []): Response
    {
        $url = $this->getUrlFor($routeName, $data, $params);
        $response = new \Slim\Psr7\Response();
        return $response
            ->withHeader('Location', $url)
            ->withStatus(302);
    }


    /**
     * Creates a redirect response.
     *
     * @param string $url
     * @param int $status
     *
     * @return Response
     */
    protected function redirectToUrl(string $url, int $status = 302): Response
    {
        $response = new \Slim\Psr7\Response();
        return $response
            ->withHeader('Location', $url)
            ->withStatus($status);
    }

    /**
     * @return RouteParserInterface
     */
    protected function getRouteParser(): RouteParserInterface
    {
        return $this->routeParser;
    }

    /**
     * Return route URL
     * @param string $routeName
     * @param array|null $data
     * @param array|null $params
     * @return string
     */
    protected function getUrlFor(string $routeName, ?array $data = [], ?array $params = []): string
    {
         return $this->routeParser->urlFor($routeName, $data, $params);
    }

    /**
     * @param $request
     * @param int $status
     * @return Response
     */
    protected function redirectToReferer($request, int $status = 302): Response
    {
        $response = new \Slim\Psr7\Response();
        return $response
            ->withHeader('Location', $request->getServerParams()['HTTP_REFERER'])
            ->withStatus($status);
    }

    /**
     * @param string $key
     * @param string $message
     * @return void
     */
    protected function addFlash(string $key, string $message): void
    {
        $this->session->getFlash()->add($key, $message);
    }

    protected function getSession(string $key)
    {
        return $this->session->get($key);
    }

    protected function getUser(): SessionUser
    {
        $session = $this->session->get('auth');
        return new SessionUser($session);
    }

    protected function pdfResponse(string $attachmentFilename): \Slim\Psr7\Response|\Slim\Psr7\Message
    {
        $response = new \Slim\Psr7\Response();
        $response = $response->withHeader('Content-Type', 'application/pdf');
        $response = $response->withHeader('Content-Transfer-Encoding', 'binary');
        $response = $response->withHeader('Accept-Ranges', 'bytes');
        //$response = $response->withHeader('Content-Length', filesize($attachmentFilename));
        return $response->withHeader('Content-Disposition',  'attachment; filename="' . $attachmentFilename . '"');
    }

    protected function setLogger(): LoggerInterface
    {
        return $this->logger;
    }

    protected function jsonResponse(string $status, $message, int $code): Response
    {
        $result = [
            'code' => $code,
            'status' => $status,
            'message' => $message
        ];
        return new JsonResponse($result, $code, [], JSON_PRETTY_PRINT);
    }
}