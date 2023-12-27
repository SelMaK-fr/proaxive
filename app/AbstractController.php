<?php
declare(strict_types=1);
namespace App;

use Awurth\Validator\StatefulValidator;
use Envms\FluentPDO\Query;
use Laminas\Diactoros\Response\RedirectResponse;
use Odan\Session\SessionInterface;
use Palmtree\Form\Form;
use Psr\Http\Message\ResponseInterface as Response;
use Selmak\Proaxive2\Settings\SettingsInterface;
use Slim\App;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\Twig;

abstract class AbstractController
{

    public function __construct(
        protected readonly Twig $twig,
        protected readonly Query $query,
        protected readonly SessionInterface $session,
        protected readonly SettingsInterface $settings,
        protected readonly RouteParserInterface $routeParser,
        protected readonly App $app,
        protected readonly StatefulValidator $validator
    ){
    }

    /**
     * Return array key and value parameters (cf /config/settings.php)
     * @param string $key
     * @param string $value
     * @return array|bool|string
     */
    protected function getParameter(string $key, string $value): array|bool|string
    {
        return $this->settings->get($key)[$value];
    }

    protected function getParameters(string $key): array|bool|string
    {
        return $this->settings->get($key);
    }

    /**
     * Return view Twig in the controller
     * @param Response $response
     * @param string $template
     * @param array|null $data
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    protected function render(Response $response, string $template, ?array $data = []): Response
    {
        $response = $response->withHeader('Content-Type', 'text/html; charset=utf-8');
        return $this->twig->render($response, $template, $data);
    }

    protected function view(string $template, ?array $data = []): mixed
    {
        return $this->twig->fetch($template, $data);
    }

    // Return the table data's in the controller. $classname => your repository class in /app/Repository
    protected function getRepository(string $classname)
    {
        return new $classname($this->query);
    }

    /**
     * @param string $type
     * @param mixed|null $data
     * @return Form
     */
    protected function createForm(string $type, mixed $data = null, ?array $args = []): Form
    {
        return (new $type($this->query))->createFormBuilder($data, $args);
    }

    protected function generateUrl(string $route): string
    {
        return $this->routeParser->urlFor($route);
    }

    /**
     * Returns a RedirectResponse to the given route
     * @param $response
     * @param string $route
     * @param int $status
     * @return RedirectResponse
     */
    protected function redirectToRoute(string $route, int $status = 302): RedirectResponse
    {
        return $this->redirect($this->generateUrl($route), $status);
    }

    /**
     * Returns a RedirectResponse to the given URL
     * @param string $url
     * @param int $status
     * @return RedirectResponse
     */
    protected function redirect(string $url, int $status = 302): RedirectResponse
    {
        return new RedirectResponse($url, $status);
    }

    /**
     * Return flash Message
     * @param string $key
     * @param string $message
     * @return void
     */
    protected function addFlash(string $key, string $message): void
    {
        $this->session->getFlash()->add($key, $message);
    }

    protected function getUserId()
    {
        return $this->session->get('auth')['id'];
    }
}