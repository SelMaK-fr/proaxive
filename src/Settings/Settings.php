<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Settings;

class Settings implements SettingsInterface
{

    private array $settings;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }
    public function get(string $key = '')
    {
        return (empty($key)) ? $this->settings : $this->settings[$key];
    }

    public function getParams(array $params = [])
    {
        // TODO: Implement getParams() method.
    }

    public function set(string $key, string $value)
    {
        return $this->settings;
    }
}