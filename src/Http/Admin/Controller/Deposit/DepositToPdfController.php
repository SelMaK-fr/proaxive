<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Deposit;

use Dompdf\Dompdf;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Deposit\Repository\DepositRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class DepositToPdfController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function generatePdf(Request $request, Response $response, array $args): Response
    {
        $deposit_id = (int)$args['id'];
        if($request->getMethod() === 'POST'){
            // Return Deposit
            $deposit = $this->getRepository(DepositRepository::class)->joinForId($deposit_id);
            // confirmation save data notification
            $this->addFlash('panel-info', 'Le dépôt a été sauvegardé.');
            // Return settings array
            $settings = $this->getParameters('storage');
            //DomPDF
            $dompdf = new Dompdf();
            $dompdf->loadHtml($this->view('/pdf/deposit_pdf.html.twig',
                ['d' => $deposit,
                 'numeric' => false
                ]), $settings['documents'] . 'deposits/Depot_' . $deposit['reference'] . '-I_' . $deposit['ref_number'].'.pdf');
        }
        return new \Slim\Psr7\Response();
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function viewDepositPdf(Request $request, Response $response, array $args): Response
    {
        /* Regen PDF IN PROGRESS */
        $reference = (int)$args['reference'];
        $isSigned = (int)$request->getQueryParams()['is_signed'];
        $deposit = $this->getRepository(DepositRepository::class)->findByReference($reference);
        $settings = $this->settings;
        /* /Regen PDF */
        if($isSigned){
            $filePdf = 'Depot_signed_' . $deposit->reference . '-I_' . $deposit->intervention_number . '.pdf';
            $pathFilePdf = $settings->get('storage')['documents']. 'deposits/signed/' . $filePdf;
        } else {
            $filePdf = 'Depot_' . $deposit->reference . '-I_' . $deposit->intervention_number . '.pdf';
            $pathFilePdf = $settings->get('storage')['documents']. 'deposits/' . $filePdf;
        }

        if(!file_exists($pathFilePdf)){
            $depositToPdf = $this->getRepository(DepositRepository::class)->joinForId((int)$deposit->id);
            $url = $settings->get('app')['domainUrl'] .'/i/' .$depositToPdf['ref_for_link'];
            $writer = new PngWriter();
            $qrCode = QrCode::create($url)
                ->setEncoding(new Encoding('UTF-8'))
                ->setSize(300)
                ->setMargin(10)
            ;
            $result = $writer->write($qrCode);
            $qrcode = $result->getDataUri();
            $dompdf = new Dompdf();
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->loadHtml($this->view('/pdf/deposit_receipt.html.twig',
                ['d' => $depositToPdf, 'qrcode' => $qrcode
                ]));
            $dompdf->render();
            $output = $dompdf->output();
            file_put_contents($pathFilePdf, $output);
        }
        $stream = fopen($pathFilePdf, 'r');
        $response = $response
            ->withHeader('Content-Type', 'application/pdf')
            ->withHeader('Content-Disposition', 'inline; filename="'.$filePdf.'"');
        return $response->withBody(new \Nyholm\Psr7\Stream($stream));
    }
}