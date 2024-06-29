<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Deposit;

use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
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
            $dataForm = $request->getParsedBody()['form_deposit'];
            $i = $this->getRepository(InterventionRepository::class)->joinForId($intervention_id);
            $data = [
                'reference' => rand(6,999999),
                'customer_name' => $i['customer_name'],
                'equipment_name' => $i['equipment_name'],
                'intervention_number' => $i['ref_number'],
                'interventions_id' => $i['id'],
                'customers_id' => $i['customers_id'],
                'equipments_id' => $i['equipments_id'],
                'company_id' => $this->getUserCompany()
            ];
            $allDataForm = $dataForm + $data;
            $save = $this->getRepository(DepositRepository::class)->add($allDataForm);
            if($save){
                // Update field with_deposit for the new deposit
                $this->getRepository(InterventionRepository::class)->update([
                    'deposit_reference' => $data['reference']
                ], (int)$i['id']);
                // confirmation save data notification
                $this->addFlash('panel-info', 'Le dépôt a été sauvegardé.');
                return $this->redirectToReferer($request);
            }
        }
    }
}