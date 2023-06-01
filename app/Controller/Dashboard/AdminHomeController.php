<?php

namespace App\Controller\Dashboard;

use Creitive\Breadcrumbs\Breadcrumbs;
use src\MyClass\Session;

class AdminHomeController extends AppController
{

    /**
     * @var string
     */
    private $current_menu;

    /**
     * @var Breadcrumbs
     */
    private Breadcrumbs $breadcrumbs;

    public function __construct()
    {
        parent::__construct();
        $this->current_menu = 'home';
        $this->breadcrumbs = new Breadcrumbs();
        // Load Model
        $this->loadModel('Client');
        $this->loadModel('Equipment');
        $this->loadModel('Intervention');
        $this->loadModel('Brand');
        $this->loadModel('Company');
        $this->loadModel('Status');
        $this->loadModel('User');
    }

    /**
     * Chargement de l'index
     */

    public function index(){

        $equipments = $this->Equipment->allWithClientCategoryBrandsOS();
        $interventions = $this->Intervention->all();
        $countClient = $this->Client->countRow();
        $clients = $this->Client->allWithDepartments();
        $countBrand = $this->Brand->countRow();
        $countCompany = $this->Company->CountRow();
        $phpversion = phpversion();
        $auser = $this->User->find('id', 1);

        $this->breadcrumbs->addCrumb('Panel Proaxive', '/admin')
            ->addCrumb('Accueil', '');
        $this->breadcrumbs->render();

        $folderInstall = ROOT . '/install';
        $checkFolderInstall = file_exists($folderInstall);

        $this->render('dashboard/home/home.twig', [
            'equipments' => $equipments,
            'interventions' => $interventions,
            'countClient' => $countClient,
            'clients' => $clients,
            'countBrand' => $countBrand,
            'countCompany' => $countCompany,
            'phpversion' => $phpversion,
            'checkFolderInstall' => $checkFolderInstall,
            'auser' => $auser,
            'current_menu' => $this->current_menu,
            'breadcrumbs' => $this->breadcrumbs
        ]);

    }

    public function statsByYear()
    {
        $this->notPermsSuperAdmin();
        $countCharts = $this->Intervention->totalAmountGraphByYear($_POST['year']);
        $interventions = $this->Intervention->all();
        $year = $_POST['year'];

        $this->breadcrumbs->addCrumb('Panel Proaxive', '/admin')
            ->addCrumb('Accueil', '')
            ->addCrumb('Statistique', '');
        $this->breadcrumbs->render();

        $this->render('dashboard/home/chartjs.twig', [
            'countCharts' => $countCharts,
            'year' => $year,
            'interventions' => $interventions,
            'current_menu' => $this->current_menu,
            'breadcrumbs' => $this->breadcrumbs
        ]);
    }

}