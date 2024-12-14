<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Settings\Account;

use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Envms\FluentPDO\Exception;
use Laminas\Diactoros\Response\RedirectResponse;
use PragmaRX\Google2FA\Google2FA;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Account\Repository\AccountRepository;
use Selmak\Proaxive2\Domain\User\Repository\UserRepository;
use Selmak\Proaxive2\Domain\User\User;
use Selmak\Proaxive2\Factory\SessionFactory;
use Selmak\Proaxive2\Helper\Hydrator;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Http\Type\AccountPasswordType;
use Selmak\Proaxive2\Http\Type\AccountTotpType;
use Selmak\Proaxive2\Http\Type\AccountType;
use Slim\Exception\HttpUnauthorizedException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AccountController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundExceptionInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index(Request $request, Response $response): Response
    {
        $id = $this->getUser()->getId();
        $a = $this->getRepository(AccountRepository::class)->find('id', $id);
        if(!$a){
            throw new HttpUnauthorizedException($request, "Account not found or session expired");
        }
        $form = $this->createForm(AccountType::class, $a);
        $form->handleRequest();

        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getRequestData()['form_account'];
            $save = $this->getRepository(AccountRepository::class)->update($data, $id);
            if($save){
                $this->addFlash('panel-info', "Votre compte utilisateur a bien été mis à jour.");
                return $this->redirectToRoute('dash_account');
            }
        }
        # Change Password (Form)
        $formPassword = $this->createForm(AccountPasswordType::class);
        $formPassword->handleRequest();

        if($formPassword->isSubmitted() && $formPassword->isValid()) {
            $data = $form->getRequestData()['form_account_password'];
            // delete password_2 field (confirm)
            unset($data['password_2']);
            // Hash password
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            $save = $this->getRepository(AccountRepository::class)->update($data, $id);
            if($save){
                $this->addFlash('panel-info', "Votre mot de passe a bien été mis à jour.");
                return $this->redirectToRoute('dash_account');
            }
        }
        $google2fa = new Google2FA();
        // TOTP 2FA (Form)
        $formToTp = $this->createForm(AccountTotpType::class);
        $formToTp->handleRequest();

        if($formToTp->isSubmitted() && $formToTp->isValid()) {
            $data = $formToTp->getRequestData()['form_account_totp'];
            if($google2fa->verifyKey($a->key_totp, $data['code'])){
                $this->addFlash('panel-info', '2FA activé avec succès.');
            } else {
                $this->addFlash('panel-error', 'Le code ne correspond pas !');
            }
        }
        // Auth 2FA
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            $a->fullname,
            $a->mail,
            $a->key_totp
        );
        $writer = new Writer(
            new ImageRenderer(
                new RendererStyle(300),
                new ImagickImageBackEnd()
            )
        );
        $google2fa_url = base64_encode($writer->writeString($qrCodeUrl));
        //
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Paramètres', false);
        $bds->addCrumb('Mon compte', false);
        $bds->render();
        //
        return $this->render($response, 'backoffice/settings/account/index.html.twig', [
           'currentMenu' => 'settings',
           'user' => $a,
           'form' => $form,
           'formPassword' => $formPassword,
           'formToTp' => $formToTp,
           'breadcrumbs' => $bds,
           'qrCodeUrl' => $google2fa_url,
           'setting_current' => 'account'
        ]);
    }

    public function on2fa(Request $request, Response $response): Response
    {
        if ($request->getMethod() === 'POST'){
            $disable = $request->getQueryParams()['disable'];
            if($disable){
                $this->getRepository(UserRepository::class)->update([
                    'key_totp' => null
                ], $this->getUser()->getId());
                $this->addFlash('panel-info', "Double authentification désactivée");
                return new RedirectResponse($this->generateUrl('dash_account'));
            }
            $google2fa = new Google2FA();
            $secretKey = $google2fa->generateSecretKey();
            $this->getRepository(UserRepository::class)->update([
                'key_totp' => $secretKey
            ], $this->getUser()->getId());
        }
        return new RedirectResponse($this->generateUrl('dash_account'));
    }

}