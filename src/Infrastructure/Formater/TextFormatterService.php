<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Infrastructure\Formater;

class TextFormatterService
{

    public function cleanText($item){
        // remplacer ces caractères ...
        $charSpec = array( 'à','â','ä','È','É','é','è','ê','ë','î','ï','ô','ù','û','ç' );
        // ... par ceux-ci
        $CharClean = array( 'a','a','a','E','E','e','e','e','e','i','i','o','u','u','c' );
        // Supprime de $titre les accents, trémas et cédilles
        $firstName = str_replace($charSpec, $CharClean, $item);
        // Convertit en minuscules
        $firstName = strtolower($firstName);
        // Remplace les caractères non-alphanumériques par des tirets
        $firstName = preg_replace("/[^A-Za-z0-9]/", '-', $firstName );
        // Remplace les tirets multiples par un tiret unique
        $firstName = preg_replace("/\-+/", '-', $firstName );
        // Supprime le dernier caractère si c'est un tiret
        $firstName = rtrim($firstName, '-');
        return $firstName;
    }
}