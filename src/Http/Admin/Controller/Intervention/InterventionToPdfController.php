<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Admin\Controller\Intervention;

use Dompdf\Dompdf;
use Dompdf\Options;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Intervention\Repository\InterventionRepository;
use Selmak\Proaxive2\Domain\Task\Repository\TaskAssocRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;

class InterventionToPdfController extends AbstractController
{
    public function __invoke(Request $request, Response $response, array $args): void
    {
        $id = (int)$args['id'];
        $intervention = $this->getRepository(InterventionRepository::class)->joinForId($id);
        $tasks = $this->getRepository(TaskAssocRepository::class)->findByIntervention($id);
        $settings = $this->settings;
        $url = $settings->get('app')['domainUrl'] .'/i/' .$intervention->ref_for_link;
        // Image (logo and signature)
        $path = $settings->get('settings')['rootPath'] . '/public';
        $logoImg = $path . '/uploads/logo/' . $intervention['cy_logo'];
        $signatureImg = $path . '/uploads/sign/' . $intervention['cy_signature'];

        $writer = new PngWriter();
        $qrCode = QrCode::create($url)
            ->setEncoding(new Encoding('UTF-8'))
            ->setSize(300)
            ->setMargin(10)
        ;
        $result = $writer->write($qrCode);
        $qrcode = $result->getDataUri();
        $option = new Options();
        $option->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($option);
        $dompdf->getOptions()->setChroot($path);
        $dompdf->setPaper('A4');
        $dompdf->loadHtml($this->view('/pdf/intervention_pdf.html.twig',
            ['i' => $intervention, 'qrcode' => $qrcode, 'tasks' => $tasks, 'logoImg' => $logoImg, 'signatureImg' => $signatureImg]));
        $dompdf->render();
        // Save PDF
        $dompdf->stream('Inter_' . $intervention['ref_number'].'.pdf', ["Attachment" => false]);
    }
}