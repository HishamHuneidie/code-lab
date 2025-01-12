<?php

namespace Hisham\CodeLab\Context\Watch\Domain\Repository;

use Hisham\CodeLab\Common\Mapper\MapperException;
use Hisham\CodeLab\Common\Repository\RepositoryException;
use Hisham\CodeLab\Context\Watch\Domain\Entity\Version;

interface VersionRepositoryInterface
{
    /**
     * Searches all PHP versions with no filters
     *
     * @return Version[]
     * @throws RepositoryException|MapperException
     */
    public function find(): array;

    /**
     * Searches one version by number
     *
     * @param string $versionNumber
     *
     * @return Version
     * @throws RepositoryException|MapperException
     */
    public function findByVersionNumber(string $versionNumber): Version;

}
