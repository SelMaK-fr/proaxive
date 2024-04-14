<?php
declare(strict_types=1);
namespace App\Controller\Account;

use App\AbstractController;
use App\Repository\UserRepository;
use App\Type\AccountPasswordType;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as v;
use Selmak\Proaxive2\Factory\MailerFactory;

class UserResetController extends AbstractController
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
    public function index(Request $request, Response $response): Response
    {
        if($request->getMethod() === 'POST'){
            $dataForm = $request->getParsedBody();
            $user = $this->getRepository(UserRepository::class)->find('mail', $dataForm['email']);
            if($user){
                $token = bin2hex($user->mail);
                $code = rand(5,99999);
                $cryptCode = password_hash((string)$code, PASSWORD_DEFAULT);
                $data = [
                    'code' => $code,
                    'fullname' => $user->fullname,
                    'mail' => $user->mail
                ];
                $pushData = [
                    'token' => $token,
                    'reset_code' => $cryptCode,
                    'reset_at' => date('Y-m-d H:i:s')
                ];
                try{
                    $this->getRepository(UserRepository::class)->update($pushData, $user->id);
                    $mail = new MailerFactory($this->getParameters('mailer'));
                    $sendmail = $mail->createMailer();
                    $sendmail->addAddress($dataForm['email']);
                    $sendmail->isHTML(true);
                    $sendmail->encodeHeader('UTF-8');
                    $sendmail->Subject = 'Réinitialiser son compte Proaxive';
                    $sendmail->msgHTML($this->view('mailer/security/reset.html.twig', ['data' => $data]));
                    $sendmail->send();
                    return $this->redirectToRoute('auth_reset_valid_code', ['token' => $token, 'id' => $user->id]);
                } catch (\Exception $e){
                    $this->session->getFlash()->add('error', $sendmail->ErrorInfo);
                }
            } else {
                $this->session->getFlash()->add('error', "Ce compte utilisateur n'existe pas !");
            }

        }
        return $this->render($response, 'security/user/forgot_password.html.twig');
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Envms\FluentPDO\Exception
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function validCodeReset(Request $request, Response $response, array $args): Response
    {
        $user_id = (int)$args['id'];
        $user = $this->getRepository(UserRepository::class)->find('id', $user_id);
        if($request->getMethod() === 'POST'){
            $data = $request->getParsedBody();
            $validator = $this->validator->validate($data, [
                'code' => [
                    'rules' => v::number(),
                    'messages' => [
                        'lenght' => 'Veuillez entrer que des chiffres'
                    ]
                ]
            ]);
            if($validator->count() === 0){
                if(password_verify($data['code'],$user->reset_code)){
                    return $this->redirectToRoute('auth_reset_password', ['token' => $user->token]);
                }
            }
        }
        return $this->render($response, 'security/user/validate_code.html.twig');
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Envms\FluentPDO\Exception
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function newPassword(Request $request, Response $response, array $args): Response
    {
        $token = (string)$args['token'];
        $user = $this->getRepository(UserRepository::class)->find('token', $token);
        $form = $this->createForm(AccountPasswordType::class);
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()){
            $data = $request->getParsedBody()['form_account_password'];
            $data['token'] = null;
            unset($data['password_2']);
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            $save = $this->getRepository(UserRepository::class)->update($data, $user->id);
            if($save){
                $this->session->getFlash()->add('info', 'Mot de passe modifié avec succès !');
                return $this->redirectToRoute('auth_user_login');
            }
        }
        return $this->render($response, 'security/user/reset_password.html.twig', [
            'form' => $form
        ]);
    }
}