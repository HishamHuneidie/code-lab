<?php

namespace Hisham\CodeLab\Common\Util;

use BadMethodCallException;

trait GetterSetterTrait
{
    public function __call(string $method, array $arguments)
    {
        $prefixCount = str_starts_with($method, 'is') ? 2 : 3;
        $prefix = substr($method, 0, $prefixCount);
        $property = substr($method, $prefixCount);

        if ($prefix === 'set') {
            $propertyName = $this->resolvePropertyName($property);

            if (!empty($propertyName)) {
                $this->$propertyName = $arguments[0];
                return $this;
            }
        }

        if ($prefix === 'get' || $prefix === 'is') {
            $propertyName = $this->resolvePropertyName($property);

            if (!empty($propertyName)) {
                return $this->$propertyName ?? null;
            }
        }

        throw new BadMethodCallException("The method {$method} does not exist.");
    }

    private function resolvePropertyName(string $property): ?string
    {
        $camelCaseProperty = lcfirst($property);
        if (property_exists($this, $camelCaseProperty)) {
            return $camelCaseProperty;
        }

        $snakeCaseProperty = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $property));
        if (property_exists($this, $snakeCaseProperty)) {
            return $snakeCaseProperty;
        }

        return null;
    }
}