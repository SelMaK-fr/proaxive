<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Settings;

class Settings implements SettingsInterface
{

    private array $settings;
    private array $overrides;

    public function __construct(array $settings, array $overrides = [])
    {
        $this->settings = $settings;
        $this->overrides = $overrides;
    }
    public function get(string $key = '')
    {
        return (empty($key)) ? $this->settings : $this->settings[$key];
    }

    public function getParams(array $params = [])
    {
        // TODO: Implement getParams() method.
    }

    public function set(string $key, string $value): void
    {
        $keys = explode('.', $key); // Permet de gérer les clés imbriquées
        $this->setRecursive($this->overrides, $keys, $value);
    }

    public function getOverrides()
    {
        return $this->overrides;
    }

    private function getRecursive($array, $keys)
    {
        $key = array_shift($keys);
        if (isset($array[$key])) {
            if (count($keys) === 0) {
                return $array[$key];
            } elseif (is_array($array[$key])) {
                return $this->getRecursive($array[$key], $keys);
            }
        }
        return null; // Retourne null si la clé n'existe pas
    }

    // Fonction récursive pour définir une valeur dans un tableau imbriqué
    private function setRecursive(&$array, $keys, $value)
    {
        $key = array_shift($keys);
        if (count($keys) === 0) {
            $array[$key] = $value;
        } else {
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }
            $this->setRecursive($array[$key], $keys, $value);
        }
    }
}