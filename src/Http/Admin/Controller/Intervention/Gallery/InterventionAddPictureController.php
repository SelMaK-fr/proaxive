<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Intervention\Gallery;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as V;
use Selmak\Proaxive2\Domain\InterventionPicture\InterventionPicture;
use Selmak\Proaxive2\Domain\InterventionPicture\Repository\InterventionPictureRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Sirius\Upload\Handler;

class InterventionAddPictureController extends AbstractController
{
    public function __invoke(Request $request, Response $response)
    {
        if($request->getMethod() === 'POST'){
            // Récupère les données du formulaire
            $data = $request->getParsedBody()['form_intervention_picture'];
            $pictureUpload = $request->getUploadedFiles()['form_intervention_picture'];
            $intervention_id = (int)$data['interventions_id'];
            $isOnline = $data['is_online'] ? 1 : 0;
            $validator = $this->validator->validate($pictureUpload, [
                'file' => [
                    'rules' => v::objectType()->attribute('file', v::oneOf(
                        v::mimetype('image/jpeg'),
                        v::mimetype('image/png'),
                        v::mimetype('image/webp')
                    ))
                ]
            ]);
            if ($validator->count() === 0){
                // PATH du stockage "Documents"
                $directory = $this->settings->get('storage')['gallery'];
                $uploadHandler = new Handler($directory . $intervention_id);
                $upload = $uploadHandler->process($pictureUpload['file']);
                if($upload->isValid()) {
                    try {
                        if(empty($data['name'])){
                            $data['name'] = $upload->name;
                        }
                        $picture = new InterventionPicture();
                        $picture->setName($data['name']);
                        $picture->setFilename($upload->name);
                        $picture->setPictureOrder((int)$data['picture_order']);
                        $picture->setDescription($data['description']);
                        $picture->setIsOnline($isOnline);
                        $picture->setInterventionsId($intervention_id);

                        $this->getRepository(InterventionPictureRepository::class)->add($picture, true);
                        $this->addFlash('panel-info', 'Fichier téléversé avec succès.');
                        $upload->confirm();
                        return $this->redirectToRoute('intervention_gallery', ['id' => $intervention_id]);
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
                return $this->redirectToReferer($request);
            }
        }
        return $this->redirectToReferer($request);
    }
}