<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Equipment\Upload;

use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as V;
use Selmak\Proaxive2\Domain\Equipment\Repository\EquipmentRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Sirius\Upload\Handler;

class EquipmentUploadPictureController extends AbstractController
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $equipment_id = (int)$args['id'];
        if($request->getMethod() == 'POST') {
            $inputRequest = $request->getUploadedFiles()['form_equipment_upload'];
            $picture = $inputRequest['file'];
            $validator = $this->validator->validate($inputRequest, [
                'file' => [
                    'rules' => v::objectType()->attribute('file', v::oneOf(
                        v::mimetype('image/jpeg'),
                        v::mimetype('image/png'),
                        v::mimetype('image/webp')
                    ))
                ]
            ]);
            if ($validator->count() === 0) {
                $path = $this->settings->get('settings')['publicPath'];
                $uploadHandler = new Handler($path . 'uploads/equipments/' . $equipment_id . '/');
                $upload = $uploadHandler->process($picture);
                if($upload->isValid()) {
                    try {
                        $this->getRepository(EquipmentRepository::class)->update([
                            'picture' => $upload->name
                        ], $equipment_id);
                        $this->addFlash('panel-info', 'Fichier téléversé avec succès.');
                        $upload->confirm();
                        return $this->redirectToRoute('equipment_update', ['id' => $equipment_id]);
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
                return $this->redirectToRoute('equipment_update', ['id' => $equipment_id]);
            }
        }
        return new RedirectResponse($this->getUrlFor('equipment_update', ['id' => $equipment_id]), 302);
    }
}