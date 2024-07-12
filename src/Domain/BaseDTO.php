<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Domain;

class BaseDTO
{
    public function __construct(?array $data = [])
    {
        $reflexion = new \ReflectionClass($this);
        foreach ($data as $key => $value) {
            if ($reflexion->hasProperty($key)) {
                $property = $reflexion->getProperty($key);
                $property->setValue($this, $value);
            }
        }
    }

    /**
     * @param $string
     * @return string
     */
    private function snakeToCamel($string)
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $string))));
    }

    /**
     * @return array
     */
    public function __toArray(): array
    {
        //return call_user_func('get_object_vars', $this);
        $reflexion = new \ReflectionClass($this);
        $properties = $reflexion->getProperties();
        $array = [];
        foreach ($properties as $property) {
            $property->setAccessible(true);
            $array[$property->getName()] = $property->getValue($this);
        }
        return $array;
    }
}