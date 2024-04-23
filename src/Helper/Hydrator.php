<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Helper;

final class Hydrator
{
    public static function hydrate(array $data, $object)
    {
        $instance = new $object();
        foreach ($data as $key => $value) {
            $method = self::getSetter($key);
            if (method_exists($instance, $method)) {
                $instance->$method($value);
            } else {
                $property = lcfirst(self::getProperty($key));
                $instance->$property = $value;
            }
        }
        return $instance;
    }

    private static function getSetter(string $fieldName): string
    {
        return 'set' . self::getProperty($fieldName);
    }

    private static function getProperty(string $fieldName): string
    {
        return join('', array_map('ucfirst', explode('_', $fieldName)));
    }
}