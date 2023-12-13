<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Customer;

use App\AbstractController;
use App\Repository\CustomerRepository;
use App\Type\CustomerType;
use App\Type\SocietyType;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Factory\RandomStringGeneratorFactory;

class CustomerCreateController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws \Envms\FluentPDO\Exception
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function particular(Request $request, Response $response): Response
    {
        $form = $this->createForm(CustomerType::class);
        $this->create($request, $response, $form);
        return $this->render($response, 'backoffice/customer/create.html.twig', [
            'form' => $form,
            'currentMenu' => 'customer'
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws \Envms\FluentPDO\Exception
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function society(Request $request, Response $response): Response
    {
        $form = $this->createForm(SocietyType::class);
        $this->create($request, $response, $form, true);
        return $this->render($response, 'backoffice/society/create.html.twig', [
            'form' => $form,
            'currentMenu' => 'customer'
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $form
     * @param bool|null $society
     * @return Response|void
     * @throws \Envms\FluentPDO\Exception
     */
    private function create(Request $request, Response $response, $form, ?bool $society = null)
    {
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getRequestData()['form_customer'];
            $data['activated'] = $data['activated'] ?? 1;
            if($society){
                $data['is_society'] = $data['is_society'] ?? 1;
            }
            $generateClientId = new RandomStringGeneratorFactory();
            $data['login_id'] = 'C-' . $generateClientId->generate(9);
            $save = $this->getRepository(CustomerRepository::class)->add($data, true);
            if($save){
                $this->session->getFlash()->add('panel-info', sprintf('Le nouveau client - %s - a bien été sauvegardé', $data['fullname']));
                return $this->redirectToRoute('dash_customer');
            }
        }
    }
}