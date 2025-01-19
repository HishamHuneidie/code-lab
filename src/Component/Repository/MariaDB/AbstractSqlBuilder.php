<?php

namespace Hisham\CodeLab\Component\Repository\MariaDB;

use BackedEnum;
use Hisham\CodeLab\Common\Enum\MariaDbTable;
use ReflectionClass;
use Stringable;

/**
 * Compile all params to build a SQL query
 */
abstract class AbstractSqlBuilder implements Stringable
{
    public function __construct(
        protected readonly MariaDbTable $table,
        protected readonly object       $entity,
    ) {}

    /**
     * Generate columns string
     *
     * @return string
     */
    protected function columns(): string
    {
        $reflectedEntity = new ReflectionClass(get_class($this->entity));

        $columns = [];
        foreach ($reflectedEntity->getProperties() as $property) {
            $property->setAccessible(true);
            $propertyName = $property->getName();
            $value = $property->getValue($this->entity);

            if ($this->isCommand() && empty($value)) continue;

            $columns[] = $this->columToSnakeCase($propertyName);
        }

        return implode(
            ', ',
            array_map(fn($column) => "`{$column}`", $columns),
        );
    }

    /**
     * Generate values string
     *
     * @return string
     */
    protected function values(): string
    {
        $reflectedEntity = new ReflectionClass(get_class($this->entity));

        $values = [];
        foreach ($reflectedEntity->getProperties() as $property) {
            $property->setAccessible(true);
            $value = $property->getValue($this->entity);

            if ($this->isCommand() && empty($value)) continue;

            if ($value instanceof BackedEnum) {
                $value = $value->value;
            }

            $values[] = $value;
        }

        return implode(
            ', ',
            array_map(fn($column) => "\"{$column}\"", $values),
        );
    }

    /**
     * Prepare the string query
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->toSql();
    }

    abstract public function toSql(): string;

    /**
     * Transform text to snake_case
     *
     * @param string $column
     *
     * @return string
     */
    private function columToSnakeCase(string $column): string
    {
        return strtolower(preg_replace(
            '/[A-Z]/',
            '_$0',
            lcfirst($column),
        ));
    }

    private function isCommand(): bool
    {
        return $this instanceof CommandBuilder;
    }

}
