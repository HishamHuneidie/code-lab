<?php

namespace Hisham\CodeLab\Context\Watch\Application\UseCase\GetRfcList;

use Hisham\CodeLab\Common\Mapper\MapperException;
use Hisham\CodeLab\Common\Repository\RepositoryException;
use Hisham\CodeLab\Component\Mapper\MapperInterface;
use Hisham\CodeLab\Context\Watch\Application\Dto\RfcDto;
use Hisham\CodeLab\Context\Watch\Application\Mapper\RfcMapper;
use Hisham\CodeLab\Context\Watch\Domain\Repository\RfcRepositoryInterface;
use Hisham\CodeLab\Context\Watch\Infrastructure\Persistence\Repository\RfcRepository;

final readonly class GetRfcList
{
    /**
     * @param RfcRepository $repository
     * @param RfcMapper     $mapper
     */
    public function __construct(
        private RfcRepositoryInterface $repository,
        private MapperInterface        $mapper,
    ) {}

    /**
     * @return RfcDto[]
     * @throws RepositoryException|MapperException
     */
    public function execute(): array
    {
        $rfcList = $this->repository->find();

        return $this->mapper->fromEntityList($rfcList);
    }

}
