<?php

namespace src\Controller;

use Aptoma\Twig\Extension\MarkdownEngine\MichelfMarkdownEngine;
use Aptoma\Twig\Extension\MarkdownExtension;
use buzzingpixel\twiggetenv\GetEnvTwigExtension;
use src\MyClass\Session;
use src\Twig\AppExtension;
use src\Twig\FrontFunctionTwig;
use Twig\Extension\CoreExtension;
use Twig\Extension\DebugExtension;
use Twig_Extensions_Extension_Intl;


class Controller
{
    /*
     * @var \Twig_Environment
     */
    protected $twig;
    protected $cache = ROOT.'/cache';
    protected $view = ROOT . 'views/';

    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader($this->view);
        $this->twig = new \Twig\Environment($loader, array(
            'cache' => false, // $cache
            'debug' => false
        ));
        $this->twig->getExtension(CoreExtension::class)->setTimezone('Europe/Paris');
        $engine = new MichelfMarkdownEngine();
        $this->twig->addExtension(new MarkdownExtension($engine));
        $this->twig->addExtension(new Twig_Extensions_Extension_Intl());
        $this->twig->addExtension(new DebugExtension());
        $this->twig->addExtension(new GetEnvTwigExtension());
        $this->twig->addExtension((new FrontFunctionTwig()));
        $this->twig->addExtension(new AppExtension());
        $this->twig->addGlobal('app_info', $this->app_version());
        $this->twig->addGlobal('app_setting', $this->app_setting());
        $this->twig->addGlobal('sessionAuth', Session::getSessionInstance()->read('auth'));
        $this->twig->addGlobal('sessionFlash', Session::getSessionInstance());

    }

    /**
     * Load template page
     * @param $filename
     * @param array $data
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    protected function render($filename, $data = []){
        $view = $this->twig->load($filename);
        echo $view->render($data);

    }

    /**
     * notFound
     */
    protected function notFound(){
        header("HTTP/1.0 404 Not Found");
        die('Page introuvable');
    }

    /**
     * Redirection header
     * @param string $url
     */
    protected function redirect(string $url){
        header("Location: $url", true, 302);
        exit();
    }

    /**
     * Permet d'ouvrir un fichier
     * @param string $file
     * @param string $mode
     * @return false|string
     */
    protected function openFile(string $file, string $mode){
        if(is_file($file)){
            $handle = fopen($file, "$mode");
            $var = fread($handle, filesize($file));
            return $var;
        } else {
            return false;
        }
    }

    /**
     * Permet de supprimer un fichier
     * @param string $file
     */
    protected function deleteFile(string $file){
        if(is_file($file)){
            unlink($file);
        }
        return false;
    }

    /**
     * Permet de supprimer plusieurs fichiers
     * @param string $folder
     */
    protected function deleteMultipeFiles(string $folder, string $extension){
        if(is_dir($folder)){
            array_map('unlink', glob("$folder/*.$extension"));
        }
        return false;
    }

    /**
     * @return string
     */
    protected function getBaseUrl(): string
    {
        // output: /myproject/index.php
        $currentPath = $_SERVER['PHP_SELF'];

        // output: Array ( [dirname] => /myproject [basename] => index.php [extension] => php [filename] => index )
        $pathInfo = pathinfo($currentPath);

        // output: localhost
        $hostName = $_SERVER['HTTP_HOST'];

        // output: http://
        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https'?'https':'http';

        // return: http://localhost/myproject/
        return $protocol.'://'.$hostName;
    }

    /**
     * Fichier de version
     * @return \SimpleXMLElement
     */
    public function app_version(){
        return $app_info = simplexml_load_file(ROOT. 'version.xml');
    }

    /**
     * Fichier de version
     * @return \SimpleXMLElement
     */
    public function app_setting(){
        return \App::getJson();
    }

}