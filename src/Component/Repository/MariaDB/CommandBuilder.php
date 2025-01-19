<?php

namespace Hisham\CodeLab\Component\Repository\MariaDB;

/**
 * Compile all params to build a SQL query
 */
final class CommandBuilder extends AbstractSqlBuilder
{

    /**
     * Prepare SQL query
     *
     * @return string
     */
    public function toSql(): string
    {
        $columns = $this->columns();
        $values = $this->values();

        return "REPLACE INTO `{$this->table->value}` ({$columns}) values ({$values})";
    }

}
