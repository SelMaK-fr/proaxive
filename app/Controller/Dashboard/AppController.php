<?php

namespace App\Controller\Dashboard;

use App;
use src\MyClass\Session;

class AppController extends App\Controller\AppController
{

    public function __construct()
    {
        parent::__construct();
        // Accès autorisé uniquement aux administrateurs
        App::getAuth()->isAdminOnly();
    }

    /**
     * Permet d'accorder l'accès uniquement aux administrateurs
     */
    public function notPermsSuperAdmin(){
        $session = Session::getSessionInstance();
        if($session->read('auth')->roles != 'super admin'){
            $session->setFlash('danger', "Permission refusée - Vous n'avez pas les droits suffisants");
            header('Location: /admin');
            exit();
        }
    }

    /**
     * Permet d'accorder l'accès uniquement aux administrateurs
     */
    public function notPermsSuperAdminOrManager(){
        $session = Session::getSessionInstance();
        if($session->read('auth')->roles === 'technician'){
            $session->setFlash('danger', "Permission refusée - Vous n'avez pas les droits suffisants");
            header('Location: /admin');
            exit();
        }
    }
}