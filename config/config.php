<?php

use Symfony\Component\Dotenv\Dotenv;

class Config
{

    private static $envLoaded = false;

    /**
     * Charge le fichier de configuration
     */
    private static function loadEnv()
    {
        if(self::$envLoaded) {
            return;
        }
        $dotenv = new Dotenv();
        $dotenv->load(dirname(__DIR__) . '.env');
        self::$envLoaded = true;
    }
}
