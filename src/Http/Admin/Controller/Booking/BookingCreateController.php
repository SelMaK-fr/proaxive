<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Admin\Controller\Booking;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Booking\Booking;
use Selmak\Proaxive2\Domain\Booking\Repository\BookingRepository;
use Selmak\Proaxive2\Domain\Intervention\Repository\InterventionRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;

class BookingCreateController extends AbstractController
{
    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function createForIntervention(Request $request, Response $response): Response
    {
        if($request->getMethod() === 'POST'){
            $data = $request->getParsedBody()['form_booking'];
            $booking = new Booking();
            $booking->setBeginAt($data['begin_at']);
            $booking->setEndAt($data['begin_at']);
            $booking->setTitle('[Retrait] ' . $data['customer']);
            $booking->setSubtitle($data['subtitle']);
            $booking->setBackgroundColor('#e44b31');
            $booking->setDescription('Retrait équipement');
            $booking->setTextColor('white');
            $booking->setAllDay(0);
            $save = $this->getRepository(BookingRepository::class)->createOject($booking);
            // update field pull_date intervention
            $updateIntervention = $this->getRepository(InterventionRepository::class)->update([
                'pull_date' => $data['begin_at']
            ], (int)$data['intervention_id']);
            if($save && $updateIntervention){
                return $this->redirectToRoute('dash_booking');
            }
        }
    }
}