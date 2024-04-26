<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Society;

use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Customer\Repository\CustomerRepository;
use Selmak\Proaxive2\Http\Admin\Controller\CrudController;
use Selmak\Proaxive2\Http\Type\Admin\SocietyType;

class SocietyUpdateController extends CrudController
{

    protected string $entity = '';

    protected string $repository = CustomerRepository::class;
    protected string $form_name = 'customer';
    protected string $routeName = '';
    protected string $template_path = 'society';
    protected string $menuItem = 'customer';

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundExceptionInterface
     */
    public function update(Request $request, Response $response, array $args)
    {
        $customer_id = (int)$args['id'];
        $customer = $this->getRepository(CustomerRepository::class)->find('id', $customer_id);
        // Breadcrumbs
        $bds = $this->app->getContainer()->get('breadcrumbs');
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Client (société)', $this->getUrlFor('dash_customer'));
        $bds->addCrumb($customer->fullname, $this->getUrlFor('customer_read', ['id' => $customer->id]));
        $bds->addCrumb('Modification', false);
        $bds->render();
        // .Breadcrumbs
        return $this->crudUpdate(SocietyType::class, $customer, $customer_id, $response, $request, $bds);

    }

}