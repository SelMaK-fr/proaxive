<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Admin\Controller\Deposit;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Selmak\Proaxive2\Domain\Deposit\Repository\DepositRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Infrastructure\Mailing\MailService;

class DepositSendPdfController extends AbstractController
{
    public function __invoke(ServerRequestInterface $request, $response): ResponseInterface
    {
        $id = (int)$request->getAttribute('id');

        $d = $this->getRepository(DepositRepository::class)->find('id', $id);

        if(!$d) {
            $this->addFlash('panel-danger', "Ce bon de dépôt n'existe pas.");
            return $this->redirectToRoute('dash_home');
        }

        $deposit = $this->getRepository(DepositRepository::class)->joinForId((int)$d->id);

        if($request->getMethod() === 'POST'){
            $mail = new MailService($this->getParameters('mailer'));
            $mail->sendMailWithAttachment(
                $deposit['c_mail'],
                $this->view('mailer/deposit/sendpdf.html.twig', ['data' => $deposit, 'setting' => $this->settings->get('app')]),
                'Votre bon de dépôt',
                $this->settings->get('storage')['documents'] . '/deposits/Depot_' . $deposit['reference'] . '-I_' . $deposit['i_reference'].'.pdf'
            );
            return $this->redirectToRoute('deposit_read', [], [
                'intervention_reference' => $deposit['i_reference'],
                'deposit_reference' => $deposit['reference']
            ]);
        }
        return $this->redirectToRoute('dash_home');
    }
}