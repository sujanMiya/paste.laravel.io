<?php

namespace App\DTO;

use Illuminate\Contracts\Support\Arrayable;

abstract class AbstractDTO implements Arrayable
{
    public function toArray(): array
    {
        $reflection = new \ReflectionClass($this);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);

        $array = [];
        foreach ($properties as $property) {
            $name = $property->getName();
            $array[$name] = $this->$name;
        }

        return $array;
    }
}
