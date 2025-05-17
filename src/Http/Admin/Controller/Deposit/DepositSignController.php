<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Deposit;

use Awurth\Validator\StatefulValidator;
use Dompdf\Dompdf;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as V;
use Selmak\Proaxive2\Domain\Company\Repository\CompanyRespository;
use Selmak\Proaxive2\Domain\Deposit\Repository\DepositRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Infrastructure\Mailing\MailService;
use Slim\App;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class DepositSignController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index(Request $request, Response $response, array $args): Response
    {
        $reference = (int)$args['reference'];
        $d = $this->getRepository(DepositRepository::class)->find('reference', $reference);
        $c = $this->getRepository(CompanyRespository::class)->find('id', $d->company_id);

        // Check if deposit as signed
        if($d->is_signed === 1){
            $this->addFlash('panel-info', "Ce dépôt a déjà été signé par le client.");
            return $this->redirectToRoute('deposit_read', [], ['intervention_reference' => $d->i_reference, 'deposit_reference' => $d->reference, 'is_signed' => 1]);
        }

        if($request->getMethod() === 'POST'){
            $data = $request->getParsedBody()['form_deposit'];
            $validator = $this->validator->validate($data, [
                'code_sign' => [
                    'rules' => v::notBlank(),
                    'messages' => [
                        'notBlank' => "Veuillez ajouter une signaure !"
                    ]
                ]
            ]);
            if($validator->count() === 0){
                $deposit = $this->getRepository(DepositRepository::class)->joinForId((int)$d->id);
                if($deposit){
                    // Return settings array
                    $settings = $this->settings;
                    // Generate QRcode
                    $url = $settings->get('app')['domainUrl'] .'/i/' .$deposit['ref_for_link'];
                    $writer = new PngWriter();
                    $qrCode = QrCode::create($url)
                        ->setEncoding(new Encoding('UTF-8'))
                        ->setSize(300)
                        ->setMargin(10)
                    ;
                    $result = $writer->write($qrCode);
                    $qrcode = $result->getDataUri();
                    // Generate PDF and save in storage folder (storage/documents/deposits)
                    $dompdf = new Dompdf();
                    $dompdf->setPaper('A4', 'landscape');
                    $dompdf->loadHtml($this->view('/pdf/deposit_receipt.html.twig',
                        ['d' => $deposit, 'data' => $data, 'qrcode' => $qrcode, 'numeric' => true
                        ]));
                    $dompdf->render();
                    // Save PDF
                    $output = $dompdf->output();
                    file_put_contents($settings->get('storage')['documents'] . '/deposits/signed/Depot_signed_' . $deposit['reference'] . '-I_' . $deposit['i_reference'].'.pdf', $output);
                    $this->addFlash('panel-info', 'Le bon de dépôt a bien été généré.');
                    // Deposit Ok (is_signed)
                    $this->getRepository(DepositRepository::class)->update(['is_signed' => 1], (int)$deposit['id'], false);
                    // Send Mail
                    if($deposit['c_mail']){
                        if(isset($data['send_mail'])){
                            $mail = new MailService($this->getParameters('mailer'));
                            $mail->sendMailWithAttachment(
                                $deposit['c_mail'],
                                $this->view('mailer/deposit/sendpdf.html.twig', ['data' => $deposit, 'setting' => $settings->get('app')]),
                                'Votre bon de dépôt',
                                $settings->get('storage')['documents'] . '/deposits/Depot_' . $deposit['reference'] . '-I_' . $deposit['i_reference'].'.pdf'
                            );
                        }
                    }
                    // Redirect
                    return $this->redirectToRoute('deposit_read', [], ['intervention_reference' => $deposit['i_reference'], 'deposit_reference' => $deposit['reference'], 'is_signed' => 1]);
                }
            }
            $this->addFlash('panel-error', "Le formulaire n'est pas rempli correctement !");
            return $this->redirectToReferer($request);
        }
        // Breadcrumbs
        $breadcrumbs = $this->breadcrumbs;
        $breadcrumbs->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $breadcrumbs->addCrumb('Dépôt', false);
        $breadcrumbs->addCrumb($this->sanitize($d->reference), false);
        $breadcrumbs->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/deposit/sign.html.twig', [
            'd' => $d,
            'c' => $c,
            'breadcrumbs' => $breadcrumbs,
            'currentMenu' => 'deposit'
        ]);
    }

    public function sendDepositByMail()
    {

    }
}