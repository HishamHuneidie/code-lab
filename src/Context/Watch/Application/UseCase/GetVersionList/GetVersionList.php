<?php

namespace Hisham\CodeLab\Context\Watch\Application\UseCase\GetVersionList;

use Hisham\CodeLab\Common\Mapper\MapperException;
use Hisham\CodeLab\Common\Repository\RepositoryException;
use Hisham\CodeLab\Component\Mapper\MapperInterface;
use Hisham\CodeLab\Context\Watch\Application\Dto\VersionDto;
use Hisham\CodeLab\Context\Watch\Application\Mapper\VersionMapper;
use Hisham\CodeLab\Context\Watch\Domain\Repository\VersionRepositoryInterface;
use Hisham\CodeLab\Context\Watch\Infrastructure\Persistence\Repository\VersionRepository;

final readonly class GetVersionList
{
    /**
     * @param VersionRepository $repository
     * @param VersionMapper     $mapper
     */
    public function __construct(
        private VersionRepositoryInterface $repository,
        private MapperInterface            $mapper,
    ) {}

    /**
     * Searches Versions
     *
     * @return VersionDto[]
     * @throws RepositoryException|MapperException
     */
    public function execute(): array
    {
        $versions = $this->repository->find();

        return $this->mapper->fromEntityList($versions);
    }

}
