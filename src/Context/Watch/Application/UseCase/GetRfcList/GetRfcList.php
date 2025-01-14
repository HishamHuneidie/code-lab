<?php

namespace Hisham\CodeLab\Context\Watch\Application\UseCase\GetRfcList;

use Hisham\CodeLab\Common\Mapper\DtoMapperException;
use Hisham\CodeLab\Common\Repository\RepositoryException;
use Hisham\CodeLab\Component\Mapper\DtoMapperInterface;
use Hisham\CodeLab\Context\Watch\Application\Dto\RfcDto;
use Hisham\CodeLab\Context\Watch\Application\Mapper\RfcDtoMapper;
use Hisham\CodeLab\Context\Watch\Domain\Repository\RfcRepositoryInterface;
use Hisham\CodeLab\Context\Watch\Infrastructure\Persistence\Repository\RfcRepository;

final readonly class GetRfcList
{
    /**
     * @param RfcRepository $repository
     * @param RfcDtoMapper  $mapper
     */
    public function __construct(
        private RfcRepositoryInterface $repository,
        private DtoMapperInterface     $mapper,
    ) {}

    /**
     * @return RfcDto[]
     * @throws RepositoryException|DtoMapperException
     */
    public function execute(): array
    {
        $rfcList = $this->repository->find();

        return $this->mapper->fromEntityList($rfcList);
    }

}
