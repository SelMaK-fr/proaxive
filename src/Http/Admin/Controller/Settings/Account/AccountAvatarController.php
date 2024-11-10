<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Admin\Controller\Settings\Account;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as V;
use Selmak\Proaxive2\Domain\User\Repository\UserRepository;
use Sirius\Upload\Handler;

class AccountAvatarController extends AccountController
{
    public function __invoke(Request $request, Response $response): Response
    {
        if($request->getMethod() === 'POST') {
            $data = $request->getParsedBody()['form_avatar'];
            $picture = $request->getUploadedFiles()['form_avatar'];
            $user_id = (int)$data['user'];
            $validator = $this->validator->validate($picture, [
                'file' => [
                    'rules' => v::objectType()->attribute('file', v::oneOf(
                        v::mimetype('image/jpeg'),
                        v::mimetype('image/png'),
                        v::mimetype('image/webp')
                    ))
                ]
            ]);
            if ($validator->count() === 0) {
                $directory = $this->settings->get('settings')['publicPath'];
                $uploadHandler = new Handler($directory . 'uploads/avatars/' . $user_id);
                $upload = $uploadHandler->process($picture['file']);
                if($upload->isValid()) {
                    try {
                        $dataUser = [
                            'avatar' => $upload->name
                        ];
                        $this->getRepository(UserRepository::class)->update($dataUser, $user_id, false);
                        $this->addFlash('panel-info', 'Fichier téléversé avec succès.');
                        $upload->confirm();
                        return $this->redirectToRoute('dash_account');
                    } catch (\Exception $e) {
                        $upload->clear();
                        throw $e;
                    }
                }
            } else {
                $errors = [];
                foreach ($validator as $failure) {
                    $errors[] = $failure->getMessage();
                }
                $this->addFlash('panel-error', $errors[0]);
                return $this->redirectToReferer($request);
            }
        }
        return $this->redirectToReferer($request);
    }
}