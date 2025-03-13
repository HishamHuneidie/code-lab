<?php

namespace Hisham\CodeLab\Context\Watch\Application\UseCase\GetVersionList;

use Hisham\CodeLab\Common\Mapper\DtoMapperException;
use Hisham\CodeLab\Common\Repository\RepositoryException;
use Hisham\CodeLab\Component\Mapper\DtoMapperInterface;
use Hisham\CodeLab\Context\Watch\Application\Dto\VersionDto;
use Hisham\CodeLab\Context\Watch\Application\Mapper\VersionDtoMapper;
use Hisham\CodeLab\Context\Watch\Domain\Repository\VersionRepositoryInterface;
use Hisham\CodeLab\Context\Watch\Infrastructure\Persistence\Repository\VersionRepository;

final readonly class GetVersionList
{
    /**
     * @param VersionRepository $repository
     * @param VersionDtoMapper  $mapper
     */
    public function __construct(
        private VersionRepositoryInterface $repository,
        private DtoMapperInterface         $mapper,
    ) {}

    /**
     * Searches Versions
     *
     * @return VersionDto[]
     * @throws RepositoryException|DtoMapperException
     */
    public function execute(): array
    {
        $versions = $this->repository->find();

        return $this->mapper->fromEntityList($versions);
    }

}
