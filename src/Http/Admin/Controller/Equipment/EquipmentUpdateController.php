<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Equipment;

use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Equipment\Repository\EquipmentRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Http\Type\Admin\Equipment\EquipmentBaoFileType;
use Selmak\Proaxive2\Http\Type\Admin\Equipment\EquipmentSpecsType;
use Selmak\Proaxive2\Http\Type\Admin\Equipment\EquipmentType;
use Selmak\Proaxive2\Http\Type\Admin\Equipment\EquipmentUploadType;
use Selmak\Proaxive2\Infrastructure\Bao\BaoService;
use Slim\Psr7\UploadedFile;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class EquipmentUpdateController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundExceptionInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function update(Request $request, Response $response, array $args): Response
    {
        $equipment_id = (int)$args['id'];
        $e = $this->getRepository(EquipmentRepository::class)->find('id', $equipment_id, true);
        $form = $this->createForm(EquipmentType::class, $e);
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getRequestData()['form_equipment'];
            // If end_guarantee is empty = null
            if(empty($data['end_guarantee'])){$data['end_guarantee'] = null;}
            if(empty($data['is_outofservice'])){$data['is_outofservice'] = null;}
            $save = $this->getRepository(EquipmentRepository::class)->update($data, $equipment_id);
            if($save) {
                $this->addFlash('panel-info', sprintf("L'équipement %s a bien été mis à jour.", $data['name']));
                return $this->redirectToReferer($request);
            }
        }
        // Form for picture upload
        $formPicture = $this->createForm(EquipmentUploadType::class);
        $formPicture->setAction($this->getUrlFor('equipment_upload_picture', ['id' => $equipment_id]));
        $formPicture->handleRequest();
        // Breadcrumbs
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Equipements', $this->getUrlFor('dash_equipment'));
        $bds->addCrumb($this->sanitize($e['customer_name']), $this->getUrlFor('customer_read', ['id' => $e['customers_id']]));
        $bds->addCrumb($this->sanitize($e['type_name']), false);
        $bds->addCrumb($this->sanitize($e['name']), false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/equipment/update.html.twig', [
            'form' => $form,
            'formPicture' => $formPicture,
            'e' => $e,
            'breadcrumbs' => $bds,
            'currentMenu' => 'equipment'
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundExceptionInterface
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
                $this->addFlash('panel-info', sprintf("La fiche technique de l'équipement %s a bien été mise à jour.", $data['name']));
                return $this->redirectToReferer($request);
            }
        }
        // Upload BAO File
        $formBao = $this->createForm(EquipmentBaoFileType::class);
        $formBao->setAction($this->getUrlFor('equipment_update_specs_upload', ['id' => $equipment_id]));
        // .Upload BAO File
        // Breadcrumbs
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Equipements', $this->getUrlFor('dash_equipment'));
        $bds->addCrumb($this->sanitize($e['customer_name']), $this->getUrlFor('customer_read', ['id' => $e['customers_id']]));
        $bds->addCrumb($this->sanitize($e['type_name']), false);
        $bds->addCrumb($this->sanitize($e['name']), $this->getUrlFor('equipment_read', ['id' => $equipment_id]));
        $bds->addCrumb('Configuration système', false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/equipment/specificies.html.twig', [
            'form' => $form,
            'formBao' => $formBao,
            'e' => $e,
            'breadcrumbs' => $bds,
            'currentMenu' => 'equipment'
        ]);
    }

    /**
     * Return data's the BAO file (.bao) with ServerRequest of Slim
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response|void
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundExceptionInterface
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
                $this->addFlash('panel-info', 'Données importées et modifications effectuées');
                return $this->redirectToRoute('equipment_update_specs', ['id' => $equipment_id]);
            } else {
                $this->addFlash('panel-error', "Veuillez charger un fichier avec l'extension .bao.");
                return $this->redirectToRoute('equipment_update_specs', ['id' => $equipment_id]);
            }
        }
    }

    /**
     * @param $directory
     * @param UploadedFile $uploadedFile
     * @return string
     * @throws \Random\RandomException
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