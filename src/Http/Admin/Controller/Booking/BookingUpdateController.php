<?php

namespace Selmak\Proaxive2\Http\Admin\Controller\Booking;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Booking\Repository\BookingRepository;
use Selmak\Proaxive2\Http\Admin\Controller\CrudController;
use Selmak\Proaxive2\Http\Type\Admin\Booking\BookingType;

class BookingUpdateController extends CrudController
{
    protected string $entity = '';

    protected string $repository = BookingRepository::class;
    protected string $form_name = 'booking';
    protected string $routeName = '';
    protected string $template_path = 'booking';
    protected string $menuItem = 'booking';

    public function __invoke(Request $request, Response $response, array $args)
    {
        $event_id = (int)$args['id'];
        $event = $this->getRepository($this->repository)->find('id', $event_id);
        // Breadcrumbs
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Evènements', $this->getUrlFor('dash_booking'));
        $bds->addCrumb($this->sanitize($event->title), false);
        $bds->addCrumb('Modification', false);
        $bds->render();
        // .Breadcrumbs
        return $this->crudUpdate(BookingType::class, $event, $event_id, $response, $request, $bds);
    }
}