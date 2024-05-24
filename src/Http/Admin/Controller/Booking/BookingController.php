<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Booking;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Booking\Repository\BookingRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;

class BookingController extends AbstractController
{
    public function index(Request $request, Response $response): Response
    {
        return $this->render($response, 'backoffice/booking/index.html.twig', [
            'currentMenu' => 'calendar'
        ]);
    }

    public function getAll(): Response
    {
        $booking = $this->getRepository(BookingRepository::class)->all();
        $events = [];
        foreach ($booking as $e){
            $events[] = [
                'id' => $e->id,
                'start' => $e->begin_at,
                'end' => $e->end_at,
                'title' => $e->title,
                'description' => $e->description,
                'color' => $e->backgroundColor,
                'textColor' => $e->textColor,
                'url' => $this->getUrlFor('dash_home')
            ];
        }
        return new JsonResponse($events, 200, []);
    }
}