<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Settings;

interface SettingsInterface
{

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key = '');

    public function getParams(array $params = []);

    public function set(string $key, string $value);
}