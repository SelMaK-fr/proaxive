<?php

namespace App\Controller;

use PDO;
use Phinx\Console\PhinxApplication;
use src\Controller\Controller;
use src\MyClass\Session;
use src\MyClass\Tools;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

class InstallerController extends Controller
{
    private $current_menu;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var mixed
     */
    private $viewJson;

    public function __construct(){
        $this->session = Session::getSessionInstance();
        parent::__construct();
        $this->current_menu = 'home';
    }

    /**
     * Page d'accueil de l'installateur
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function home(){
        $checkExtensionIntl = extension_loaded('php8.1-intl');
        $checkExtensionXml = extension_loaded('php8.1-xml');
        $clientPHPVersion = PHP_VERSION;
        $checkPHP = version_compare(PHP_VERSION, '8.1', '>=');

        $this->render('install/home.twig', [
            'checkExtensionIntl' => $checkExtensionIntl,
            'checkExtensionXml' => $checkExtensionXml,
            'clientPHPVersion' => $clientPHPVersion,
            'checkPHP' => $checkPHP,
            'current_menu' => 'home'
        ]);
    }

    /**
     * Page d'accueil de l'installateur (licence)
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function licence(){
        $licence = htmlspecialchars(file_get_contents(ROOT . '/gpl.txt'));
        $this->render('install/licence.twig', [
            'licence' => $licence,
            'current_menu' => 'home'
        ]);
    }

    /**
     * Inscrit les informations de la base de données dans le Json
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function database(){
        // Vérifie la version de PHP
        $this->checkPHP();
        // Récupération des données du formulaire
        if(!empty($_POST)){
            if(!empty($_POST['db_host']) && !empty($_POST['db_name']) && !empty($_POST['db_user']) && !empty($_POST['db_passwd'])){
                $writeJson = array(
                    'db_host' => htmlspecialchars($_POST['db_host'], ENT_QUOTES, 'UTF-8'),
                    'db_name' => htmlspecialchars($_POST['db_name'], ENT_QUOTES, 'UTF-8'),
                    'db_user' => htmlspecialchars($_POST['db_user'], ENT_QUOTES, 'UTF-8'),
                    'db_passwd' => $_POST['db_passwd']
                );
                // Vérifie si la connexion à la base de données est OK
                $postDB = $this->checkDB($_POST['db_name'], $_POST['db_host'], $_POST['db_user'], $_POST['db_passwd']);
                if($postDB != null){
                    // OK !
                    if($writeJson){
                        file_put_contents(ROOT . 'src/Database/db.json', json_encode($writeJson));
                        $this->session->setFlash('success', 'Les informations ont bien été sauvegardées, <strong>veuillez patienter...</strong>');
                        header("Location: /setup/migrate");
                    } else {
                        $this->session->setFlash('danger', 'Un souci, <strong>veuillez patienter...</strong>');
                        return $this->home();
                    }
                } else {
                    // Erreur -> retour PDO $e
                    $postDB;
                }
            } else {
                $this->session->setFlash('danger', "Le formulaire n'a pas été rempli complétement !");

            }
        }
        $this->render('install/database.twig', [
            'current_menu' => 'database'
        ]);
    }

    /**
     * Importe le fichiers .sql vers la base de données
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function migrate(){
        $data = '';
        $success = '';
        // Vérifie la version de PHP
        $this->checkPHP();

        // Vérifie si le fichier Json de configuration est rempli SI NON redirige vers
        // install/database
        if(!$this->readJson()->db_host OR !$this->readJson()->db_user){
            $this->session->setFlash('danger', "<strong>Erreur !</strong> Vérifiez vos informations de base de données et recommencez !");
            return $this->database();
        }
        // Importe les données du fichier .sql vers la base de données
        if (isset($_POST['migrate'])) {
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
                //$this->writeXMLVersion();
            }
        }
        $this->render('install/importsql.twig', [
            'viewJson' => $this->readJson(),
            'current_menu' => 'importsql',
            'success' => $success,
            'response' => $data
        ]);
    }

    /**
     * @return void|null
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function seeding()
    {
        $data = '';
        $success = '';
        // Vérifie la version de PHP
        $this->checkPHP();

        // Vérifie si le fichier Json de configuration est rempli SI NON redirige vers
        // install/database
        if(!$this->readJson()->db_host OR !$this->readJson()->db_user){
            $this->session->setFlash('danger', "<strong>Erreur !</strong> Vérifiez vos informations de base de données et recommencez !");
            return $this->database();
        }
        // Importe les données du fichier .sql vers la base de données
        if (isset($_POST['seeding'])) {
            $phinx = new PhinxApplication();
            $phinx->setAutoExit(false);

            $input = new ArrayInput(array(
                'command' => 'seed:run',
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
                $this->session->setFlash('success', "L'ajout des données a correctement été effectuée.");
                // Inscription du processus dans la session
                $data = $response;
                $success = 1;
                //$this->writeXMLVersion();
            }
        }
        $this->render('install/seeding.twig', [
            'viewJson' => $this->readJson(),
            'current_menu' => 'importsql',
            'success' => $success,
            'response' => $data
        ]);
    }

    /**
     * Permet de choisir quelques parametres
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function setting(){
        // Vérifie la version de PHP
        $this->checkPHP();
        // Vérifie si le fichier Json de configuration est rempli SI NON redirige vers
        // install/database
        if(!$this->readJson()->db_host OR !$this->readJson()->db_user){
            $this->session->setFlash('danger', "<strong>Erreur !</strong> Vérifiez vos informations de base de données et recommencez !");
            return $this->database();
        }
        $baseUrl = $this->getBaseUrl();
        if(!empty($_POST)){
            if(isset($_POST['setting'])) {
                $writeJson = array(
                    'php_error' => $_POST['php_error'],
                    'full_error' => $_POST['full_error'],
                    'app_url' => htmlspecialchars($_POST['app_url'], ENT_QUOTES, 'UTF-8'),
                    'style_selector' => 0,
                    'view_version' => 1,
                    'app_login_url' => 'login-dash'
                );
                if($writeJson){
                    file_put_contents(ROOT. '/config/setting.json', json_encode($writeJson));
                    $this->session->setFlash('success', "Paramètres sauvegardés...<strong>Veuillez patienter...</strong> !");
                    header("Location: /setup/finish");
                } else {
                    $this->session->setFlash('danger', "Un problème est survenu !");
                }
            }

        }
        $this->render('install/setting.twig', [
            'current_menu' => 'setting',
            'baseUrl' => $baseUrl
        ]);
    }

    /**
     * Met fin à l'installation de l'application
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function finish(){
        // Vérifie si le fichier .tar existe bien
        $checkTar = ROOT . 'src';
        // Vérifie la version de PHP
        $this->checkPHP();
        // Vérifie si le fichier Json de configuration est rempli SI NON redirige vers
        // install/database
        if(!$this->readJson()->db_host OR !$this->readJson()->db_user){
            $this->session->setFlash('danger', "<strong>Erreur !</strong> Vérifiez vos informations de base de données et recommencez !");
            return $this->database();
        }

        if(!empty($_POST)){
            unlink(ROOT. "/public/install.lock");
            $this->session->setFlash('success', 'Installation terminée !...<strong>veuillez patienter...</strong>');
            header("Refresh: 2;url=/login-dash");
        }

        $this->render('install/finish.twig', [
            'current_menu' => 'finish'
        ]);
    }

    /**
     * $db_name, $db_host, $db_user, $db_passwd
     * @param $db_name
     * @param $db_host
     * @param $db_user
     * @param $db_passwd
     * @return PDO
     */
    private function checkDB($db_name, $db_host, $db_user, $db_passwd){
        try{
            $db = new PDO('mysql:dbname=' . $db_name . ';host='.$db_host . '', $db_user, $db_passwd);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db;
        } catch (\PDOException $e){
            $this->session->setFlash('danger', 'Impossible de se connecter à la base de données, <strong>veuillez vérifier vos informations de connexion</strong> : '. $e->getMessage());
        }
    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function checkPHP(){
        $checkPHP = version_compare(PHP_VERSION, '8.1.0', '>=');
        if(!$checkPHP){
            return $this->home();
        }
    }

    /**
     *
     */
    private function readJson(){
        $viewJsonFile = file_get_contents(ROOT . '/src/Database/db.json');
        return json_decode($viewJsonFile, false);
    }

    /**
     * Réinitialise le fichier de configuration Json
     */
    private function emptyJson(){
        $writeJson = array(
            'db_host' => '',
            'db_name' => '',
            'db_user' => '',
            'db_passwd' => ''
        );
        file_put_contents(ROOT . '/config/setting.json', json_encode($writeJson));
    }

}