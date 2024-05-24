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
                'start' => $e->begin_at,
                'end' => $e->end_at,
                'title' => $e->title,
                'description' => $e->description,
                'backgroundColor' => $e->backgroundColor,
                'textColor' => $e->textColor
            ];
        }
        return new JsonResponse($events, 200, []);
    }
}