<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Admin\Controller\Workshop\Upload;

use Awurth\Validator\Validator;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Workshop\Repository\WorkshopRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Respect\Validation\Validator as V;
use Sirius\Upload\Handler;

class WorkshopUpdateSignatureController extends AbstractController
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $workshop_id = (int)$args['id'];

        if($request->getMethod() === 'POST'){
            $inputRequest = $request->getUploadedFiles()['form_workshop_signature_upload'];
            $signature = $inputRequest['signature'];
            $validator = $this->validator->validate($inputRequest, [
                'signature' => [
                    'rules' => v::objectType()->attribute('file', v::oneOf(
                        v::mimetype('image/jpeg'),
                        v::mimetype('image/png')
                    ))
                ]
            ]);
            if ($validator->count() === 0) {
                $path = $this->settings->get('settings')['publicPath'];
                $uploadHandler = new Handler($path . 'uploads/sign');
                $upload = $uploadHandler->process($signature);
                if($upload->isValid()) {
                    try {
                        $save = $this->getRepository(WorkshopRepository::class)->update([
                            'signature' => $upload->name
                        ], $workshop_id);
                        if($save){
                            $this->addFlash('panel-info', 'Fichier téléversé avec succès.');
                            $upload->confirm();
                            return $this->redirectToRoute('workshop_update', ['id' => $workshop_id]);
                        }
                    } catch (\Exception $e) {
                        $upload->clear();
                        throw $e;
                    }
                } else {
                    $this->addFlash('panel-error', sprintf('Erreur lors du téléversement : %', $upload->getMessages()));
                }
            } else {
                $errors = [];
                foreach ($validator as $failure) {
                    $errors[] = $failure->getMessage();

                }
                $this->addFlash('panel-error', $errors[0]);
                return $this->redirectToRoute('workshop_update', ['id' => $workshop_id]);
            }

        }
        return new RedirectResponse($this->getUrlFor('workshop_update', ['id' => $workshop_id]), 302);
    }
}