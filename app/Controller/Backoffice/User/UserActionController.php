<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\User;

use App\AbstractController;
use App\Repository\UserRepository;
use App\Type\UserType;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserActionController extends AbstractController
{

    public function action(Request $request, Response $response, array $args): Response
    {
        $user_id = (int)$args['id'];
        $user = null;
        if($user_id){
            $user = $this->getRepository(UserRepository::class)->find('id', $user_id, true);
            $form = $this->createForm(UserType::class, $user);
        } else {
            $form = $this->createForm(UserType::class);
        }
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getRequestData()['form_user'];
            unset($data['password_2']);
            if($user_id){
                $saveUpdate = $this->getRepository(UserRepository::class)->update($data, $user_id);
                if($saveUpdate){
                    $this->session->getFlash()->add('panel-info', sprintf("L'utilisateur - %s - a bien été modifié.", $data['fullname']));
                    return $this->redirect($request->getServerParams()['HTTP_REFERER']);
                }
            } else {
                $save = $this->getRepository(UserRepository::class)->add($data, true);
                if($save){
                    $this->session->getFlash()->add('panel-info', sprintf("L'utilisateur - %s - a bien été créé.", $data['fullname']));
                    return $this->redirectToRoute('dash_user');
                }
            }
        }

        return $this->render($response, 'backoffice/user/action.html.twig', [
            'form' => $form,
            'currentMenu' => 'user',
            'u' => $user
        ]);
    }
}