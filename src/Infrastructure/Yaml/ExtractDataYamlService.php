<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Infrastructure\Yaml;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\Yaml\Yaml;

class ExtractDataYamlService implements ExtractDataYamlInterface
{
    public function __construct(private readonly string $file)
    {
    }
    public function get(string $section, string $key = '')
    {
        $dotenv = new \Symfony\Component\Dotenv\Dotenv();
        $dotenv->loadEnv(dirname(__DIR__, 3) . '/.env');
        $config = Yaml::parseFile($this->file);
        $expression = new ExpressionLanguage();
        array_walk_recursive($config, function (&$value) use ($expression) {
            if(is_string($value) && preg_match('/%env\((\w+)\)%/', $value, $matches)){
                $envVariable = $matches[1];
                $value = $_ENV[$envVariable] ?? null;
            }
        });
        if(isset($config[$section])) {
            return $config[$section][$key] ?? sprintf('Clé introuvable dans la section %s', $section);
        } else {
            return sprintf("Section %s introuvable", $section);
        }
    }
}