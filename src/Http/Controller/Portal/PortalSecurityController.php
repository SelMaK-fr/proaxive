<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Controller\Portal;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator;
use Selmak\Proaxive2\Domain\Customer\CustomerPwdDTO;
use Selmak\Proaxive2\Domain\Customer\Repository\CustomerRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Http\Type\Customer\CustomerPasswordType;

class PortalSecurityController extends AbstractController
{

    public function index(Request $request, Response $response): Response
    {
        $id = $this->getSession('customer')['id'];
        $dto = new CustomerPwdDTO();
        $form = $this->createForm(CustomerPasswordType::class, $dto);
        $form->handleRequest();

        if($form->isSubmitted() && $form->isValid()) {
            $validator = $this->validator->validate($dto, [
                'passwd' => [
                    'rules' => Validator::length(6,20)
                ],
                'passwd_2' => [
                    'rules' => Validator::equals($dto->passwd)->length(6,20)
                ]
            ]);
            if($validator->count() === 0) {
                $passHash = password_hash($dto->passwd, PASSWORD_BCRYPT);
                $data = [
                    'passwd' => $passHash
                ];
                $save = $this->getRepository(CustomerRepository::class)->update($data, $id);
                if($save) {
                    $this->addFlash('success', 'Votre mot de passe a bien été mis à jour.');
                    return $this->redirectToRoute('portal_parameters');
                }
            } else {
                foreach ($validator as $v){
                    $this->addFlash('error', sprintf('%s', $v->getMessage()));
                }
                return $this->redirectToReferer($request);
            }
        }

        return $this->render($response, 'frontoffice/portal/parameter/security.html.twig', [
            'form' => $form
        ]);
    }
}