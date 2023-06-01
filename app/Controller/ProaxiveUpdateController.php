<?php


namespace App\Controller;


use Phinx\Console\PhinxApplication;
#use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
#use SensioLabs\AnsiConverter\Theme\SolarizedXTermTheme;
use src\Form\SpectreForm;
use src\MyClass\Paginator;
use src\MyClass\Session;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class ProaxiveUpdateController extends AppController
{
    private $current_menu;

    /**
     * @var Session
     */
    private $session;

    public function __construct(){

        parent::__construct();
        \App::getAuth()->isAdminOnly();
        $this->current_menu = 'home';
        $this->session = Session::getSessionInstance();
        // Charge les différentes tables de la base de données
        $this->loadModel('ProaxiveUpdate');
        $this->loadModel('User');

    }

    /**
     * Permet d'afficher la liste des interventions
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function home(){
        //$this->checkUpdate();
        $error = '';
        if(!empty($_POST)) {
            if(!empty($_POST['passData'])) {
                $userData = htmlspecialchars($_POST['userData'], ENT_QUOTES, 'UTF-8');
                // Récupère les données du fichier Json dédié à la DB (login, hôte etc.)
                $viewJsonFile = file_get_contents(ROOT . '/src/Database/db.json');
                $viewJson = json_decode($viewJsonFile, false);
                if($_POST['passData'] === $viewJson->db_passwd && $userData === $viewJson->db_user) {
                    $this->session->write('proaxive_update', 1);
                    $this->redirect('/update/database-update');
                } else {
                    $this->session->setFlash('danger', 'Identifiants incorrect !');
                    $error = "Veuillez vérifier vos informations de connexion !";
                }
            }
        }
        $this->render('install/update/home.twig', [
            'error' => $error,
            'current_menu' => 'database'
        ]);
    }

    /**
     * 
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function updateDatabaseByField(){

        if(!empty($_POST['number_version'])){
            $result = $this->User->createFields([
                'key_totp VARCHAR(100) NULL'
            ]);
            /*$update = $this->Intervention->updateFields([
                'received datetime NULL'
            ]);*/
            if($result){
                $this->session->setFlash('success', 'La base de données a été mise à jour !');
            }
        }
        $form = new SpectreForm($_POST);
        $this->render('dashboard/update/model.twig', [
            'form' => $form,
            'current_menu' => 'database'
        ]);

    }

    /**
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function updateValidated(){
        $this->render('update/updated.twig');
    }

    /**
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function updateDatabase(){
        $this->checkUpdate();
        $data = '';
        $success = '';

        if(!empty($_POST)){
            $phinx = new PhinxApplication();
            $phinx->setAutoExit(false);

            $input = new ArrayInput(array(
                'command' => 'migrate',
                '-c' => ROOT . 'phinx.php',
                '-e' => 'production',
            ));

            $output = new BufferedOutput(
                OutputInterface::VERBOSITY_NORMAL,
                true
            );
            $phinx->run($input, $output);


            //$convert = new AnsiToHtmlConverter(new SolarizedXTermTheme());
            $response = $output->fetch();
            //$console = $convert->convert($response);

            $allDone = strpos($response, 'All Done');
            if($allDone === false){
                $this->session->setFlash('danger', "Une erreur est survenue, plus de détails ci-dessous :");
                $success = 0;
                $data = $response;
            } else {
                $this->session->setFlash('success', "La migration a correctement été effectuée.");
                // Inscription du processus dans la session
                $data = $response;
                $success = 1;
                $this->session->delete('proaxive_update');
                //$this->writeXMLVersion();
            }
        }
        $form = new SpectreForm($_POST);
        $this->render('install/update/updatedatabase.twig', [
            'form' => $form,
            'response' => $data,
            'success' => $success,
            'current_menu' => 'database'
        ]);
    }

    /**
     * Permet de sauvegarder la base de données de Proaxive
     */
    public function mysqldumpData(){
        // Charge le fichier Json d'accès à la base de données
        $host = getenv('DB_HOST');
        $database = getenv('DB_DATABASE');
        $user = getenv('DB_USERNAME');
        $password = getenv('DB_PASSWORD');
        // Charge Mysqldump
        $dumper = new IMysqldump\Mysqldump('mysql:host='.$host.';dbname='. $database, $user, $password);
        // Envoi le fichier .sql vers le dossier de sauvegarde
        $dumper->start(ROOT.'db/save/dump-'.time().'.sql');
        if($dumper){
            $this->session->setFlash('success', 'La base de données a été sauvegardée !');
            $this->updateDatabase();
        }
    }

    /**
     * @return void
     * Permet de vérifier la dernière version de Proaxive via le serveur SynexoLabs
     */
    private function checkUpdate(){
        if(!$this->session->read('proaxive_update')) {
            $this->redirect('/');
            $this->session->setFlash('danger', "La session n'existe pas !");
        }
    }

    /**
     * Update XML for update = 1
     * @return void
     */
    private function writeXMLVersion(){
        $xml = simplexml_load_file(ROOT . 'version.xml');
        $xml->app_version->chk_update = 1;
        $xml->asXML(ROOT . 'version.xml');
    }

}