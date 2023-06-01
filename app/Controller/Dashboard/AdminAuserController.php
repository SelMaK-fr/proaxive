<?php


namespace App\Controller\Dashboard;


use App;
use Creitive\Breadcrumbs\Breadcrumbs;
use Otp\GoogleAuthenticator;
use Otp\Otp;
use ParagonIE\ConstantTime\Base32;
use ParagonIE\ConstantTime\Encoding;
use src\Auth\Auth;
use src\Form\SpectreForm;
use src\MyClass\Session;

class AdminAuserController extends AppController
{

    /**
     * @var string
     */
    private $current_menu;
    /**
     * @var Auth
     */

    private Auth $auth;

    /**
     * @var Session
     */
    private Session $session;

    /**
     * @var Breadcrumbs
     */
    private Breadcrumbs $breadcrumbs;


    public function __construct(){

        parent::__construct();
        $this->session = Session::getSessionInstance();
        $this->auth = new Auth(app::getInstance()->getDB());
        $this->current_menu = 'ausers';
        $this->breadcrumbs = new Breadcrumbs();
        // Charge les tables de la base de données
        $this->loadModel('User');
        $this->loadModel('Roleauser');
    }

    /**
     * Permet de lister les administrateurs
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function home(){
        $this->notPermsSuperAdminOrManager();
        $ausers = $this->User->allWithRoles();

        //Breadcrumb
        $this->breadcrumbs->addCrumb('Accueil', '/admin')
            ->addCrumb('Utilisateurs', '');
        $this->breadcrumbs->render();

        $this->render('ausers/admin/home.twig', [
            'ausers' => $ausers,
            'current_menu' => $this->current_menu,
            'breadcrumbs' => $this->breadcrumbs
        ]);

    }

    /**
     * Permet d'ajouter un administrateur
     *
     * @return type
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add(){
        $this->notPermsSuperAdminOrManager();
        $date = date('Y-m-d H:i:s');
        if(!empty($_POST) && !empty($_POST['pseudo']) && !empty($_POST['password'])){
            $password = $_POST['password'];
            $pass_hash = password_hash($password, PASSWORD_BCRYPT);
            $result = $this->User->create([
                'pseudo' => $_POST['pseudo'],
                'fullname' => $_POST['fullname'],
                'mail' => $_POST['mail'],
                'password' => $pass_hash,
                'resetpassword' => false,
                'lastvisite' => $date,
                'token' => false,
                'roles' => $_POST['roles'],
                'created_at' => $date,
                'updated_at' => $date
            ]);
            if($result){
                $this->session->setFlash('success', "Le nouvel utilisateur {$_POST['fullname']} a été sauvegardé.");
                return $this->home();
            }
            else {
                $this->session->setFlash('danger', "<strong>Aie ! il y a un souci</strong> Errror data");
            }
        }

        //Breadcrumb
        $this->breadcrumbs->addCrumb('Accueil', '/admin')
            ->addCrumb('Utilisateurs', '')
            ->addCrumb('Nouvel utilisateur', '');
        $this->breadcrumbs->render();

        $form = new SpectreForm($_POST);
        $this->render('ausers/admin/add.twig', [
            'form' => $form,
            'current_menu' => $this->current_menu,
            'breadcrumbs' => $this->breadcrumbs
        ]);


    }

    /**
     * Permet d'éditer un administrateur
     *
     * @param int $id
     * @return
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit(int $id){
        $this->notPermsSuperAdminOrManager();

        $this->editUser($id);
        $auser = $this->User->find('id', (int) $id);

        // Vérifie si le compte administrateur existe dans la base de données
        if(empty($auser)){
            $this->session->setFlash('danger', "Ce compte administrateur n'existe pas.");
            return $this->home();
        }

        //Breadcrumb
        $this->breadcrumbs->addCrumb('Accueil', '/admin')
            ->addCrumb('Utilisateurs', '')
            ->addCrumb($auser->pseudo, '');
        $this->breadcrumbs->render();

        $form = new SpectreForm($auser);
        $this->render('ausers/admin/edit.twig', [
            'auser' => $auser,
            'form' => $form,
            'current_menu' => $this->current_menu,
            'breadcrumbs' => $this->breadcrumbs
        ]);

    }

    /**
     * Permet d'activer l'authentification à double facteur
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function totp(){
        if(!empty($_POST)){
            if(isset($_POST['code'])){
                $otp = new Otp();
                if($otp->checkTotp(Encoding::base32DecodeUpper($this->session->read('secret')), $_POST['code'])){
                    $this->User->update(
                        'id', (int) $this->auth->getUserId(),
                        [
                            'key_totp' => $this->session->read('secret')
                        ]);
                    $this->session->delete('secret');
                    $this->session->setFlash('success', "L'authentification à double facteurs a bien été activée !");
                    return $this->edit($this->auth->getUserId());
                } else {
                    $this->session->setFlash('danger', 'Ce code ne correspond pas !');
                }
            }
        }

        $secret = GoogleAuthenticator::generateRandom();
        $this->session->write('secret', $secret);
        $qrcode = GoogleAuthenticator::getQrCodeUrl('totp', 'ProaxiveLite', $secret);

        //Breadcrumb
        $this->breadcrumbs->addCrumb('Accueil', '/admin')
            ->addCrumb('Utilisateurs', '/admin/ausers')
            ->addCrumb($this->session->read('auth')->pseudo, '/admin/edit-auser/'.$this->session->read('auth')->id)
            ->addCrumb('Authentification à double facteur', '');
        $this->breadcrumbs->render();

        $form = new SpectreForm($_POST);
        $this->render('ausers/admin/totp.twig', [
            'qrcode' => $qrcode,
            'secret' => $secret,
            'form' => $form,
            'current_menu' => $this->current_menu,
            'breadcrumbs' => $this->breadcrumbs
        ]);
    }

    /**
     * Permet de désactiver l'authentification à double facteur
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function totpDesactivate(){
        if(!empty($_POST)){
            if(isset($_POST['key_totp'])){
                $result = $this->User->update(
                    'id', (int) $this->auth->getUserId(),
                    [
                        'key_totp' => null
                    ]);
                if($result){
                    $this->session->setFlash('success', "L'authentification à double facteurs a bien été désactivée !");
                    return $this->home();
                } else {
                    $this->session->setFlash('danger', "Erreur lors de la mise à jour de la base de données !");
                }

            }
        }
    }

    /**
     * Permet de supprimer un administrateur
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function delete(){
        if(!empty($_POST)){
            $this->Auser->delete('id', $_POST['id']);
            $this->session->setFlash('success', 'Le compte utilisateur a bien été supprimé');
            return $this->home();
        }
    }

    public function myAccount()
    {
        $auser = $this->User->find('id', (int) $this->session->read('auth')->id);
        $this->editUser($this->session->read('auth')->id);
        //Breadcrumb
        $this->breadcrumbs->addCrumb('Accueil', '/admin')
            ->addCrumb('Utilisateurs', '')
            ->addCrumb($this->session->read('auth')->pseudo, '');
        $this->breadcrumbs->render();

        $form = new SpectreForm($auser);
        $this->render('ausers/admin/myaccount.twig', [
            'auser' => $auser,
            'form' => $form,
            'current_menu' => $this->current_menu,
            'breadcrumbs' => $this->breadcrumbs
        ]);
    }

    private function editUser($id)
    {
        $date = date('Y-m-d H:i:s');
        if(!empty($_POST)){

            // Modifie le mot de passe du compte administrateur
            if(isset($_POST['editpassword']))
            {
                $confirmpassword = $_POST['confirmpassword'];
                $password = $_POST['password'];
                // Permet de vérifier si le mot de passe correspond
                if($password != $confirmpassword)
                {
                    $this->session->setFlash('danger', "Le mot de passe ne correspond pas !");

                }else{
                    // Permet de crypter le mot de passe
                    $pass_hash = password_hash($password, PASSWORD_BCRYPT);
                    // Envoi les données à la table
                    $updatepassword = $this->User->update(
                        'id', (int) $id,
                        [
                            'password' => $pass_hash,
                            'token' => null,
                            'updated_at' => $date
                        ]);
                    if($updatepassword){
                        $this->session->setFlash('success', "Le mot de passe a été changé. Il sera effectif à votre prochaine connexion.");
                        return $this->redirect('/admin/ausers');
                    }
                }
                // Fin de vérification du mot de passe

            }
            // Fin de la function edition de mot de passe

            if(isset($_POST['updateaccount']))
            {
                // Met à jour les données du compte administrateur
                $result = $this->User->update(
                    'id', (int) $id,
                    [
                        'pseudo' => $_POST['pseudo'],
                        'fullname' => $_POST['fullname'],
                        'mail' => $_POST['mail'],
                        'roles' => $_POST['roles'],
                        'resetpassword' => false,
                        'lastvisite' => $date,
                        'token' => null,
                        'updated_at' => $date
                    ]);
                if($result){
                    $this->session->setFlash('success', "La modification a été effectuée (Account).");
                    return $this->redirect('/admin/ausers');
                }
                // Fin de la mise à jour du compte administrateur
            }
        }
    }

}