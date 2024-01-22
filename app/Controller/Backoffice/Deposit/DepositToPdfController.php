<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Deposit;

use App\AbstractController;
use App\Repository\DepositRepository;
use App\Repository\InterventionRepository;
use App\Repository\TypeEquipmentRepository;
use App\Service\GeneratePdfService;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Dompdf\Dompdf;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use GuzzleHttp\Psr7\LazyOpenStream;
use Knp\Snappy\Pdf;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Service\RandomNumberService;
use Selmak\Proaxive2\Service\SerialNumberFormatterService;
use Slim\Psr7\Stream;

class DepositToPdfController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function generatePdf(Request $request, Response $response, array $args): Response
    {
        $deposit_id = (int)$args['id'];
        if($request->getMethod() === 'POST'){
            // Return Deposit
            $deposit = $this->getRepository(DepositRepository::class)->joinForId($deposit_id);
            // confirmation save data notification
            $this->session->getFlash()->add('panel-info', 'Le dépôt a été sauvegardé.');
            // Return settings array
            $settings = $this->app->getContainer()->get('settings');
            //DomPDF
            $dompdf = new Dompdf();
            $dompdf->loadHtml($this->twig->fetch('/snappy/deposit_pdf.html.twig',
                ['d' => $deposit
                ]), $settings['storage']['documents'] . 'deposits/Depot_' . $deposit['reference'] . '-I_' . $deposit['ref_number'].'.pdf');
        }
        return new \Slim\Psr7\Response();
    }

    public function viewDepositPdf(Request $request, Response $response, array $args): Response
    {
        /* Regen PDF IN PROGRESS */
        $reference = (int)$args['reference'];
        $deposit = $this->getRepository(DepositRepository::class)->findByReference($reference);
        $pathStorage = $this->app->getContainer()->get('settings')['storage'];
        /* /Regen PDF */
        $filePdf = 'Depot_' . $deposit->reference . '-I_' . $deposit->intervention_number . '.pdf';
        $pathFilePdf = $pathStorage['documents']. 'deposits/' . $filePdf;

        if(!file_exists($pathFilePdf)){
            $depositToPdf = $this->getRepository(DepositRepository::class)->joinForId($deposit->id);
            $settings = $this->app->getContainer()->get('settings');
            $url = $settings['app']['domainUrl'] .'/i/' .$depositToPdf['ref_for_link'];
            $writer = new PngWriter();
            $qrCode = QrCode::create($url)
                ->setEncoding(new Encoding('UTF-8'))
                ->setSize(300)
                ->setMargin(10)
            ;
            $result = $writer->write($qrCode);
            $qrcode = $result->getDataUri();
            $dompdf = new Dompdf();
            $dompdf->loadHtml($this->twig->fetch('/snappy/deposit_pdf.html.twig',
                ['d' => $depositToPdf, 'qrcode' => $qrcode
                ]));
            $dompdf->render();
            $output = $dompdf->output();
            file_put_contents($settings['storage']['documents'] . 'deposits/Depot_' . $reference . '-I_' . $deposit->intervention_number.'.pdf', $output);
        }
        $stream = fopen($pathFilePdf, 'r');
        $response = $response
            ->withHeader('Content-Type', 'application/pdf')
            ->withHeader('Content-Disposition', 'inline; filename="'.$filePdf.'"');
        return $response->withBody(new \Nyholm\Psr7\Stream($stream));
    }
}