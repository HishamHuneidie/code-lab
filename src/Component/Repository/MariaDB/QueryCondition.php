<?php

namespace Hisham\CodeLab\Component\Repository\MariaDB;

use Stringable;

/**
 * Condition to be added into a SQL query in where clause
 */
final class QueryCondition implements Stringable
{
    private const OPERATORS = [
        '=',
        '>',
        '<',
        '>=',
        '<=',
        '!=',
        'LIKE',
        'NOT LIKE',
        'IN',
    ];

    /**
     * @throws MariaDbException
     */
    public function __construct(
        public readonly string $column,
        public string          $operator,
        public readonly string $value,
    )
    {
        $this->operator = strtoupper($this->operator);

        if (!in_array($this->operator, self::OPERATORS)) {
            throw new MariaDbException("Operator {$this->operator} is not supported");
        }
    }

    public function __toString(): string
    {
        if ($this->operator === 'IN') {
            return "`{$this->column}` IN ({$this->value})";
        }

        if ($this->operator === 'LIKE' || $this->operator === 'NOT LIKE') {
            return "`{$this->column}` {$this->operator} \"%{$this->value}%\"";
        }

        return "`{$this->column}` {$this->operator} \"{$this->value}\"";
    }

}
