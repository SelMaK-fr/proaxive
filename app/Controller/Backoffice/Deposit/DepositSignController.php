<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Deposit;

use App\AbstractController;
use App\Repository\CompanyRespository;
use App\Repository\DepositRepository;
use App\Repository\InterventionRepository;
use Dompdf\Dompdf;
use Knp\Snappy\Pdf;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as V;

class DepositSignController extends AbstractController
{

    public function index(Request $request, Response $response, array $args): Response
    {
        $reference = (int)$args['reference'];
        $d = $this->getRepository(DepositRepository::class)->find('reference', $reference);
        $c = $this->getRepository(CompanyRespository::class)->find('id', $d->company_id);

        if($request->getMethod() === 'POST'){
            $data = $request->getParsedBody();
            $validator = $this->validator->validate($data, [
                'code_sign' => [
                    'rules' => v::notBlank(),
                    'messages' => [
                        'notBlank' => "Veuillez ajouter une signaure !"
                    ]
                ]
            ]);
            if($validator->count() === 0){
                $deposit = $this->getRepository(DepositRepository::class)->joinForId($d->id);
                if($deposit){
                    // Return settings array
                    $settings = $this->app->getContainer()->get('settings');
                    // Generate PDF and save in storage folder (storage/documents/deposits)
                    $dompdf = new Dompdf();
                    $dompdf->loadHtml($this->twig->fetch('/snappy/deposit_pdf.html.twig',
                        ['d' => $deposit, 'data' => $data
                        ]));
                    $dompdf->render();
                    // Save PDF
                    $output = $dompdf->output();
                    file_put_contents($settings['storage']['documents'] . '/deposits/Depot_' . $deposit['reference'] . '-I_' . $deposit['i_reference'].'.pdf', $output);
                    $this->session->getFlash()->add('panel-info', 'Le bon de dépôt a bien été généré.');
                    $url = $this->routeParser->urlFor('deposit_read', [], ['intervention_reference' => $deposit['i_reference'], 'deposit_reference' => $deposit['reference']]);
                    return $response->withStatus(302)->withHeader('Location', $url);
                }
            }
            $this->session->getFlash()->add('panel-error', "Le formulaire n'est pas rempli correctement !");
            return $this->redirect($request->getServerParams()['HTTP_REFERER']);
        }
        return $this->render($response, 'backoffice/deposit/sign.html.twig', [
            'd' => $d,
            'c' => $c,
            'currentMenu' => 'deposit'
        ]);
    }
}