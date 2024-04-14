<?php
declare(strict_types=1);
namespace App;

use Awurth\Validator\StatefulValidator;
use Envms\FluentPDO\Query;
use mysql_xdevapi\Exception;
use Odan\Session\SessionInterface;
use Palmtree\Form\Form;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Selmak\Proaxive2\Settings\SettingsInterface;
use Slim\App;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\Twig;

abstract class AbstractController
{

    public function __construct(
        protected readonly App $app
        //protected readonly StatefulValidator $validator
    ){
    }

    /**
     * @param string $key
     * @param string $value
     * @return array|bool|string
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getParameter(string $key, string $value): array|bool|string
    {
        return $this->app->getContainer()->get(SettingsInterface::class)->get($key)[$value];
    }

    /**
     * @param string $key
     * @return array|bool|string
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getParameters(string $key): array|bool|string
    {
        return $this->app->getContainer()->get(SettingsInterface::class)->get($key);
    }

    /**
     * @param Response $response
     * @param string $template
     * @param array|null $data
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function render(Response $response, string $template, ?array $data = []): Response
    {
        $response = $response->withHeader('Content-Type', 'text/html; charset=utf-8');
        return $this->app->getContainer()->get(Twig::class)->render($response, $template, $data);
    }

    /**
     * @param string $template
     * @param array|null $data
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function view(string $template, ?array $data = []): mixed
    {
        return $this->app->getContainer()->get(Twig::class)->fetch($template, $data);
    }

    /**
     * Return the table data's in the controller. $classname => your repository class in /app/Repository
     * @param string $classname
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getRepository(string $classname): mixed
    {
        try {
            return new $classname($this->app->getContainer()->get(Query::class));
        } catch (\Exception $e){
            return new Exception('Repository empty');
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
    protected function createForm(string $type, mixed $data = null, ?array $args = []): Form
    {
        return (new $type($this->app->getContainer()->get(Query::class)))->createFormBuilder($data, $args);
    }

    /**
     * @param string $route
     * @return string
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
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
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
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
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getRouteParser(): mixed
    {
        return $this->app->getContainer()->get(RouteParserInterface::class);
    }

    /**
     * Return route URL
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getUrlFor(string $routeName, ?array $data = [], ?array $params = []): mixed
    {
        return $this->app->getContainer()->get(RouteParserInterface::class)->urlFor($routeName, $data, $params);
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
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function addFlash(string $key, string $message): void
    {
        $this->app->getContainer()->get(SessionInterface::class)->getFlash()->add($key, $message);
    }

    protected function getSession(string $key)
    {
        return $this->app->getContainer()->get(SessionInterface::class)->get($key);
    }

    protected function setSession(string $key, mixed $value)
    {
        return $this->app->getContainer()->get(SessionInterface::class)->set($key, $value);
    }

    protected function deleteSession(string $key)
    {
        return $this->app->getContainer()->get(SessionInterface::class)->delete($key);
    }

    /**
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getUserId(): mixed
    {
        return $this->app->getContainer()->get(SessionInterface::class)->get('auth')['id'];
    }

    /**
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getUserCompany(): mixed
    {
        return $this->app->getContainer()->get(SessionInterface::class)->get('auth')['company_id'];
    }

    protected function pdfResponse(string $attachmentFilename)
    {
        $response = new \Slim\Psr7\Response();
        $response = $response->withHeader('Content-Type', mime_content_type($filename));
        $response = $response->withHeader('Content-Transfer-Encoding', 'binary');
        $response = $response->withHeader('Accept-Ranges', 'bytes');
        return $response->withHeader('Content-Length', filesize($filename));
    }
}