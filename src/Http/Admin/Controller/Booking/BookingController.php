<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Booking;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Booking\Repository\BookingRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Infrastructure\Calendar\Calendar;
use Selmak\Proaxive2\Infrastructure\Calendar\Events;
use Selmak\Proaxive2\Infrastructure\Calendar\Month;

class BookingController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function fullcalendar(Request $request, Response $response): Response
    {

        // Breadcrumbs
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Evènements', false);
        $bds->addCrumb('Calendrier', false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/booking/calendar.html.twig', [
            'currentMenu' => 'calendar',
            'breadcrumbs' => $bds,
            'calendar_url' => $this->settings->get('app')['calendar_url']
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws \Envms\FluentPDO\Exception
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function all(Request $request, Response $response): Response
    {
        $events = $this->getRepository(BookingRepository::class)->all();
        // Breadcrumbs
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Evènements', false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/booking/index.html.twig', [
            'events' => $events,
            'breadcrumbs' => $bds,
        ]);
    }

    public function getAll(): Response
    {
        $booking = $this->getRepository(BookingRepository::class)->all();
        $events = [];
        foreach ($booking as $e){
            $events[] = [
                'id' => $e->id,
                'start' => $e->start_date . 'T' . $e->start_time,
                'end' => $e->end_date . 'T' . $e->end_time,
                'title' => $e->title,
                'description' => $e->subtitle,
                'allDay' => $e->allDay,
                'color' => $e->backgroundColor,
                'textColor' => $e->textColor,
                'url' => $this->getUrlFor('dash_home')
            ];
        }
        return new JsonResponse($events, 200, []);
    }
}