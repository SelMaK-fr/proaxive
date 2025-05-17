<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Admin\Controller\Outlay;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Outlay\Outlay;
use Selmak\Proaxive2\Domain\Outlay\Repository\OutlayRepository;
use Selmak\Proaxive2\Http\Admin\Controller\CrudController;
use Selmak\Proaxive2\Http\Type\Admin\OutlayType;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class OutlayUpdateController extends CrudController
{
    protected string $entity = Outlay::class;

    protected string $repository = OutlayRepository::class;
    protected string $form_name = 'outlay';
    protected string $routeName = '';
    protected string $template_path = 'outlay';
    protected string $menuItem = 'outlay';

    /**
     * @throws NotFoundExceptionInterface
     * @throws SyntaxError
     * @throws ContainerExceptionInterface
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $outlay = $this->getRepository($this->repository)->find('id', (int)$args['id']);
        // Breadcrumbs
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Débours', false);
        $bds->addCrumb($this->sanitize($outlay->reference), false);
        $bds->render();
        // .Breadcrumbs
        return $this->crudUpdate(OutlayType::class, $outlay, (int)$args['id'], $response, $request, $bds);
    }
}