<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Admin\Controller\Booking;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Booking\Repository\BookingRepository;
use Selmak\Proaxive2\Http\Admin\Controller\CrudController;

class BookingDeleteController extends CrudController
{

    protected string $repository = BookingRepository::class;
    protected string $routeName = 'booking_all';

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        return $this->crudDelete($this->repository, $response, $request, $args);
    }
}