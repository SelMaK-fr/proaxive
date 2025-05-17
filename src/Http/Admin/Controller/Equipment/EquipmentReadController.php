<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Equipment;

use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Customer\Repository\CustomerRepository;
use Selmak\Proaxive2\Domain\Equipment\Repository\EquipmentRepository;
use Selmak\Proaxive2\Domain\Intervention\Repository\InterventionRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Slim\Exception\HttpNotFoundException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class EquipmentReadController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function read(Request $request, Response $response, array $args): Response
    {
        $equipment_id = (int)$args['id'];
        $e = $this->getRepository(EquipmentRepository::class)->findWithTypeBool($equipment_id);
        if(!$e){
            throw new HttpNotFoundException($request, 'This equipment could not be found. Please check your database.');
        }
        $c = $this->getRepository(CustomerRepository::class)->find('id', $e->customers_id);
        $i = $this->getRepository(InterventionRepository::class)->allBy('equipments_id', $e->id);
        //
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Equipements', $this->getUrlFor('dash_equipment'));
        $bds->addCrumb($this->sanitize($e->customer_name), $this->getUrlFor('customer_read', ['id' => $e->customers_id]));
        $bds->addCrumb($this->sanitize($e->te_name), false);
        $bds->addCrumb($this->sanitize($e->name), false);
        $bds->render();
        //
        return $this->render($response, 'backoffice/equipment/read.html.twig', [
            'currentMenu' => 'equipment',
            'e' => $e,
            'c' => $c,
            'i' => $i,
            'breadcrumbs' => $bds
        ]);
    }
}