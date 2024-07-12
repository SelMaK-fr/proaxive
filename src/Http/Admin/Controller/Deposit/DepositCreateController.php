<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Deposit;

use DateTime;
use Dompdf\Dompdf;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Envms\FluentPDO\Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Deposit\Deposit;
use Selmak\Proaxive2\Domain\Deposit\Repository\DepositRepository;
use Selmak\Proaxive2\Domain\Intervention\Repository\InterventionRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;

class DepositCreateController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function create(Request $request, Response $response, array $args): Response
    {
        $intervention_id = (int)$args['id'];
        if($request->getMethod() === 'POST'){
            $form = $request->getParsedBody()['form_deposit'];
            $i = $this->getRepository(InterventionRepository::class)->joinForId($intervention_id);
            $deposit = new Deposit();
            $deposit->setReference((string)rand(6,999999));
            $deposit->setCustomerName($i['customer_name']);
            $deposit->setEquipmentName($i['equipment_name']);
            $deposit->setInterventionNumber($i['ref_number']);
            $deposit->setInterventionsId($i['id']);
            $deposit->setCustomersId($i['customers_id']);
            $deposit->setEquipmentsId($i['equipments_id']);
            $deposit->setCompanyId($this->getUser()->getCompanyId());
            $deposit->setEquipmentState((int)$form['equipment_state']);
            $deposit->setEquipmentAccessories($form['equipment_accessories']);
            $deposit->setDepositDate($form['deposit_date']);
            $deposit->setIsSigned(0);
            $save = $this->getRepository(DepositRepository::class)->add($deposit, false);
            $lastIdDeposit = $this->getRepository(DepositRepository::class)->lastInsertId();
            if($save){
                // Update field with_deposit for the new deposit
                $this->getRepository(InterventionRepository::class)->update([
                    'deposit_reference' => $deposit->getReference(),
                    'deposit_date' => $deposit->getDepositDate(),
                ], (int)$i['id']);
                // Create PDF

                $findDeposit = $this->getRepository(DepositRepository::class)->joinForId((int)$lastIdDeposit);
                // Generate QRCode
                $url = $this->settings->get('app')['domainUrl'] .'/i/' .$findDeposit['ref_for_link'];
                $writer = new PngWriter();
                $qrCode = QrCode::create($url)
                    ->setEncoding(new Encoding('UTF-8'))
                    ->setSize(300)
                    ->setMargin(10)
                ;
                $result = $writer->write($qrCode);
                // Save QRCode
                $qrcode = $result->getDataUri();
                $dompdf = new Dompdf();
                $dompdf->setPaper('A4', 'landscape');
                $dompdf->loadHtml($this->view('/pdf/deposit_receipt.html.twig',
                    ['d' => $findDeposit, 'qrcode' => $qrcode,
                     'numeric' => false
                    ]));
                $dompdf->render();
                // Save PDF
                $output = $dompdf->output();
                file_put_contents($this->settings->get('storage')['documents'] . '/deposits/Depot_' . $findDeposit['reference'] . '-I_' . $findDeposit['i_reference'].'.pdf', $output);
                // confirmation save data notification
                $this->addFlash('panel-info', 'Le dépôt a été sauvegardé.');
                return $this->redirectToReferer($request);
            }
        }
    }
}