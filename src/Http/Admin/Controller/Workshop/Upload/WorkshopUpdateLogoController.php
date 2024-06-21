<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Admin\Controller\Workshop\Upload;

use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as V;
use Selmak\Proaxive2\Domain\Workshop\Repository\WorkshopRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Sirius\Upload\Handler;

class WorkshopUpdateLogoController extends AbstractController
{
    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Envms\FluentPDO\Exception
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $workshop_id = (int)$args['id'];

        if($request->getMethod() === 'POST'){
            $inputRequest = $request->getUploadedFiles()['form_workshop_upload'];
            $logo = $inputRequest['logo'];
            $validator = $this->validator->validate($inputRequest, [
                'logo' => [
                    'rules' => v::objectType()->attribute('file', v::oneOf(
                        v::mimetype('image/jpeg'),
                        v::mimetype('image/png')
                    ))
                ]
            ]);
            if ($validator->count() === 0) {
                $path = $this->settings->get('settings')['publicPath'];
                $uploadHandler = new Handler($path . 'uploads/logo');
                $upload = $uploadHandler->process($logo);
                if($upload->isValid()) {
                    try {
                        $this->getRepository(WorkshopRepository::class)->update([
                            'logo' => $upload->name
                        ], $workshop_id);
                        $this->addFlash('panel-info', 'Fichier téléversé avec succès.');
                        $upload->confirm();
                        return $this->redirectToRoute('workshop_update', ['id' => $workshop_id]);
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