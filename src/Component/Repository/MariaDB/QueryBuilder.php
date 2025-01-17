<?php

namespace Hisham\CodeLab\Component\Repository\MariaDB;

/**
 * Compile all params to build a SQL query
 */
final class QueryBuilder extends AbstractSqlBuilder
{
    /** @var QueryCondition[] */
    private array $conditions = [];

    /**
     * Add where or conditions
     *
     * @param QueryCondition ...$conditions
     *
     * @return $this
     */
    public function where(QueryCondition ...$conditions): self
    {
        foreach ($conditions as $condition) {
            $this->conditions[] = $condition;
        }
        return $this;
    }

    /**
     * Prepare SQL query
     *
     * @return string
     */
    public function toSql(): string
    {
        $columns = $this->columns();

        $sql = "SELECT {$columns} FROM `{$this->table->value}`";

        if (!empty($this->conditions)) {
            $conditions = implode(' AND ', $this->conditions);
            $sql .= " WHERE {$conditions}";
        }

        return $sql;
    }

}
