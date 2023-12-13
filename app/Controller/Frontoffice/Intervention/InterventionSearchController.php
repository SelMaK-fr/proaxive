<?php
declare(strict_types=1);
namespace App\Controller\Frontoffice\Intervention;

use App\AbstractController;
use App\Repository\InterventionRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as V;

class InterventionSearchController extends AbstractController
{

    public function search(Request $request, Response $response): Response
    {
        if($request->getMethod() === 'GET'){
            $param = $request->getQueryParams();
            $validator = $this->validator->validate($param, [
               's' => [
                   'rules' => V::notBlank()->length(6,25)->noWhitespace()->stringType(),
                   'messages' => [
                       'length' => 'This field must have a length between 6 and 25 characters and contain only letters and digits',
                       'noWhitespace' => 'Aucun espace dans notre numéro',
                   ]
               ]
            ]);
            if($validator->count() === 0){
                $i = $this->getRepository(InterventionRepository::class)->searchWithNumber($param['s'])->fetch();
                if($i){
                    return $response->withStatus(302)->withHeader('Location', $this->routeParser->urlFor('app_read_intervention', ['ref_for_link' => $i['ref_for_link']]));
                } else {
                    $this->session->getFlash()->add('error', 'Aucune intervention pour ce numéro !');
                    return $response->withStatus(302)->withHeader('Location', $request->getServerParams()['HTTP_REFERER']);
                }
            } else {
                foreach ($validator as $failure) {
                    $this->session->getFlash()->add('error', sprintf("Raison -> %s", $failure->getMessage()));
                }
                return $response->withStatus(302)->withHeader('Location', $request->getServerParams()['HTTP_REFERER']);
            }
        }
    }
}