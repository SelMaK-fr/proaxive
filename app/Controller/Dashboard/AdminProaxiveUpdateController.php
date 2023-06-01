<?php


namespace App\Controller\Dashboard;


use Creitive\Breadcrumbs\Breadcrumbs;
use src\Form\SpectreForm;
use src\MyClass\Paginator;
use src\MyClass\Session;

class AdminProaxiveUpdateController extends AppController
{
    private $current_menu;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var Breadcrumbs
     */
    private Breadcrumbs $breadcrumbs;

    public function __construct(){

        parent::__construct();
        $this->notPermsSuperAdmin();
        $this->current_menu = 'home';
        $this->session = Session::getSessionInstance();
        $this->breadcrumbs = new Breadcrumbs();
        // Charge les différentes tables de la base de données
        $this->loadModel('ProaxiveUpdate');
        $this->loadModel('User');
        $this->loadModel('Setting');

    }

    /**
     * Permet d'afficher la liste des interventions
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function home(){
        $this->render('dashboard/home/home.twig');
    }

    /**
     * 
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function updateDatabase(){
        $setting = $this->Setting->find('id', 1);
        //Breadcrumb
        $this->breadcrumbs->addCrumb('Accueil', '/admin')
            ->addCrumb('Proaxive Update', '/admin/update')
            ->addCrumb('Mise à jour', '');
        $this->breadcrumbs->render();
        $newupdate = '';
        $serverhost = '';
        if(!empty($_POST)) {
            $viewJsonFile = file_get_contents('https://proaxive.fr/version.json');
            $serverhost = json_decode($viewJsonFile, false);
            if($setting->version != $serverhost->num){
                $newupdate = 1;
            } else{
                $newupdate = null;
            }
        }

        $form = new SpectreForm($_POST);
        $this->render('dashboard/update/home.twig', [
            'form' => $form,
            'setting' => $setting,
            'serverhost' => $serverhost,
            'newupdate' => $newupdate,
            'current_menu' => 'update',
            'breadcrumbs' => $this->breadcrumbs
        ]);

    }
}