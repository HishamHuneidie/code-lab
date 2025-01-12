<?php

namespace Hisham\CodeLab\Context\Watch\Domain\Repository;

use Hisham\CodeLab\Common\Mapper\MapperException;
use Hisham\CodeLab\Common\Repository\RepositoryException;
use Hisham\CodeLab\Context\Watch\Domain\Entity\PathnameVo;
use Hisham\CodeLab\Context\Watch\Domain\Entity\Rfc;

interface RfcRepositoryInterface
{
    /**
     * Searches a list of RFCs
     *
     * @return Rfc[]
     * @throws RepositoryException|MapperException
     */
    public function find(): array;

    /**
     * Searches the content of a RFCs
     *
     * @param PathnameVo $pathnameVo
     *
     * @return Rfc
     * @throws RepositoryException
     * @throws MapperException
     */
    public function findByPathname(PathnameVo $pathnameVo): Rfc;

}
