<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Settings;

use Envms\FluentPDO\Exception;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Brand\Repository\BrandRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Http\Type\Admin\BrandType;
use Selmak\Proaxive2\Infrastructure\Formater\TextFormatterService;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class BrandController extends AbstractController
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
        $brands = $this->getRepository(BrandRepository::class)->all();
        $form = $this->createForm(BrandType::class);
        $form->setAction($this->getUrlFor('settings_brand_create'));
        $form->handleRequest();
        //
        $breadcrumbs = $this->breadcrumbs;
        $breadcrumbs->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $breadcrumbs->addCrumb('Paramètres', false);
        $breadcrumbs->addCrumb('Marques', false);
        $breadcrumbs->render();
        //
        return $this->render($response, 'backoffice/settings/brands/index.html.twig', [
            'currentMenu' => 'settings',
            'brands' => $brands,
            'setting_current' => 'brand',
            'form' => $form,
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws \Exception
     */
    public function actionForm(Request $request, Response $response, array $args): Response
    {
        if($request->getMethod() === 'POST') {
            $id = (int)$request->getQueryParams()['id'];
            if($id != 0) {
                $data = $request->getParsedBody();
            } else {
                $data = $request->getParsedBody()['form_brand'];
            }
            $uploadedFiles = $request->getUploadedFiles()['form_brand']; // For logo
            $uploadedFile = $uploadedFiles['logo_file'];
            $path = $this->settings->get('settings')['publicPath']. '/uploads/brands';
            if($uploadedFile->getError() === UPLOAD_ERR_OK) {
                $manager = new ImageManager(new Driver());
                if(!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                $renameFile = bin2hex(random_bytes(8)). '.png';
                $manager->read($uploadedFile->getFilePath())->scale(130,null)->save($path . '/'.$renameFile);
                $data['logo_file'] = $renameFile;
            }
            $textFormatter = new TextFormatterService();
            $data['slug'] = $textFormatter->cleanText($data['name']);
            $checkIfExist = $this->getRepository(BrandRepository::class)->ifExist('name', $data['name']);
            if($id != 0) {
                $this->getRepository(BrandRepository::class)->update($data, $id, false);
            } else {
                if($checkIfExist != 1) {
                    $this->getRepository(BrandRepository::class)->add($data);
                    $this->addFlash('panel-info', "Action effectuée avec succès.");
                } else {
                    $this->addFlash('panel-error', "Cet élément existe déjà !");
                }
            }
            return $this->redirectToRoute('settings_brand');
        }
        return new \Slim\Psr7\Response();
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws Exception
     */
    public function delete(Request $request, Response $response): Response
    {
        if($request->getMethod() === 'DELETE'){
            $data = $request->getParsedBody();
            if($data){
                unset($data['_METHOD']);
                $this->getRepository(BrandRepository::class)->delete((int)$data['id']);
                $this->addFlash('panel-info', "Marque supprimée.");
                return $this->redirectToReferer($request);
            }
        }
        return new \Slim\Psr7\Response();
    }
}