<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\API\V1\Booking;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Booking\Repository\BookingRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;

class ApiGetAll extends AbstractController
{
    public function __invoke(Request $request, Response $response): Response
    {
        $booking = $this->getRepository(BookingRepository::class)->all();
        $events = [];
        foreach ($booking as $e){
            $events[] = [
                'id' => $e->id,
                'start' => $e->start_date . 'T' . $e->start_time,
                'end' => $e->end_date . 'T' . $e->end_time,
                'title' => $e->title,
                'description' => $e->description,
                'allDay' => $e->allDay,
                'color' => $e->backgroundColor,
                'textColor' => $e->textColor,
                'url' => $this->getUrlFor('dash_home'),
                'cal_timer' => [
                    'start_time' => $e->start_time,
                    'end_time' => $e->end_time,
                ]
            ];
        }
        return new JsonResponse($events, 200, []);
    }
}