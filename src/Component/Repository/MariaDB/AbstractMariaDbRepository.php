<?php

namespace Hisham\CodeLab\Component\Repository\MariaDB;

use PDO;

class AbstractMariaDbRepository
{
    public function __construct(
        private readonly PDO $db,
    ) {}

    /**
     * Search only one record in DB
     *
     * @param QueryBuilder $builder
     *
     * @return array|null
     * @throws MariaDbException
     */
    protected function selectOne(QueryBuilder $builder): ?array
    {
        $this->validate($builder);

        $result = $this->db->query((string)$builder);
        $data = $result->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as $row) {
            return $row;
        }

        return null;
    }

    /**
     * Search records in DB
     *
     * @param QueryBuilder $builder
     *
     * @return array
     * @throws MariaDbException
     */
    protected function select(QueryBuilder $builder): array
    {
        $this->validate($builder);

        $result = $this->db->query((string)$builder);
        $data = $result->fetchAll(PDO::FETCH_ASSOC);

        $rows = [];
        foreach ($data as $row) {
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * Execute query to update row and throw exception in case it fails
     *
     * @param CommandBuilder $builder
     *
     * @return void
     * @throws MariaDbException
     */
    protected function commandSave(CommandBuilder $builder): void
    {
        $this->validate($builder);

        $result = $this->db->query((string)$builder);

        if ($result === false) {
            throw new MariaDbException('Failed to save data');
        }
    }

    private function validate(?AbstractSqlBuilder $builder = null): void
    {
        if (empty($builder)) {
            throw new MariaDbException('Table is not set');
        }
    }

}