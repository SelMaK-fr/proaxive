<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Equipment;

use App\AbstractController;
use App\Repository\EquipmentRepository;
use App\Service\BaoService;
use App\Type\EquipmentBaoFileType;
use App\Type\EquipmentSpecsType;
use App\Type\EquipmentType;
use App\Type\EquipmentUpdateType;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\UploadedFile;

class EquipmentUpdateController extends AbstractController
{
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
    public function update(Request $request, Response $response, array $args): Response
    {
        $equipment_id = (int)$args['id'];
        $customer_id = '';
        $e = $this->getRepository(EquipmentRepository::class)->find('id', $equipment_id, true);
        $form = $this->createForm(EquipmentType::class, $e);
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getRequestData()['form_equipment'];
            $save = $this->getRepository(EquipmentRepository::class)->update($data, $equipment_id);
            if($save) {
                $this->session->getFlash()->add('panel-info', sprintf("L'équipement %s a bien été mis à jour.", $data['name']));
                return $this->redirect($request->getServerParams()['HTTP_REFERER']);
            }
        }
        return $this->render($response, 'backoffice/equipment/update.html.twig', [
            'form' => $form,
            'e' => $e,
            'currentMenu' => 'equipment'
        ]);
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
    public function specificies(Request $request, Response $response, array $args): Response
    {
        $equipment_id = (int)$args['id'];
        $e = $this->getRepository(EquipmentRepository::class)->find('id', $equipment_id, true);
        $form = $this->createForm(EquipmentSpecsType::class, $e);
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getRequestData()['form_equipment_specs'];
            $save = $this->getRepository(EquipmentRepository::class)->update($data, $equipment_id);
            if($save) {
                $this->session->getFlash()->add('panel-info', sprintf("La fiche technique de l'équipement %s a bien été mise à jour.", $data['name']));
                return $this->redirect($request->getServerParams()['HTTP_REFERER']);
            }
        }
        // Upload BAO File
        $formBao = $this->createForm(EquipmentBaoFileType::class);
        $formBao->setAction($this->routeParser->urlFor('equipment_update_specs_upload', ['id' => $equipment_id]));
        // .Upload BAO File
        return $this->render($response, 'backoffice/equipment/specificies.html.twig', [
            'form' => $form,
            'formBao' => $formBao,
            'e' => $e,
            'currentMenu' => 'equipment'
        ]);
    }

    /**
     * Return data's the BAO file (.bao) with ServerRequest of Slim
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response|void
     * @throws \Envms\FluentPDO\Exception
     */
    public function baoUpload(Request $request, Response $response, array $args)
    {
        $equipment_id = (int)$args['id'];
        if($request->getMethod() === 'POST') {
            // Accept Extension
            $allowedExt = [
              'bao'
            ];
            $data = $request->getUploadedFiles()['form_fileupload_bao'];
            $directory = $this->getParameter('storage', 'temp');
            $file = $data['file'];
            // Check file extension
            $fileExtension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
            if($file->getError() === UPLOAD_ERR_OK && in_array($fileExtension, $allowedExt)) {
                $filename = self::moveUploadedFile($directory, $file);
                $bao = new BaoService();
                $parserBao = $bao->parser_array($directory . '/' . $filename, true);
                $this->getRepository(EquipmentRepository::class)->update($parserBao, $equipment_id);
                unlink($directory . '/' . $filename);
                $this->session->getFlash()->add('panel-info', 'Données importées et modifications effectuées');
                return $response->withStatus(302)->withHeader('Location', $this->routeParser->urlFor('equipment_update_specs', ['id' => $equipment_id]));
            } else {
                $this->session->getFlash()->add('panel-error', "Veuillez charger un fichier avec l'extension .bao.");
                return $response->withStatus(302)->withHeader('Location', $this->routeParser->urlFor('equipment_update_specs', ['id' => $equipment_id]));
            }
        }
    }

    /**
     * @param $directory
     * @param UploadedFile $uploadedFile
     * @return string
     * @throws \Exception
     */
    private function moveUploadedFile($directory, UploadedFile $uploadedFile): string
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }
}