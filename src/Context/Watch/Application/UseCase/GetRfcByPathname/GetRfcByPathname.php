<?php

namespace Hisham\CodeLab\Context\Watch\Application\UseCase\GetRfcByPathname;

use Hisham\CodeLab\Common\Mapper\MapperException;
use Hisham\CodeLab\Common\Repository\RepositoryException;
use Hisham\CodeLab\Component\Mapper\MapperInterface;
use Hisham\CodeLab\Context\Watch\Application\Dto\EscapedPathnameVoDto;
use Hisham\CodeLab\Context\Watch\Application\Dto\RfcDto;
use Hisham\CodeLab\Context\Watch\Application\Mapper\PathnameVoMapper;
use Hisham\CodeLab\Context\Watch\Application\Mapper\RfcMapper;
use Hisham\CodeLab\Context\Watch\Domain\Repository\RfcRepositoryInterface;
use Hisham\CodeLab\Context\Watch\Infrastructure\Persistence\Repository\RfcRepository;

final readonly class GetRfcByPathname
{
    /**
     * @param RfcRepository              $repository
     * @param RfcMapper|PathnameVoMapper $mapper
     */
    public function __construct(
        private RfcRepositoryInterface $repository,
        private MapperInterface        $mapper,
    ) {}

    /**
     * @param EscapedPathnameVoDto $pathnameVoDto
     *
     * @return RfcDto
     * @throws MapperException
     * @throws RepositoryException
     */
    public function execute(EscapedPathnameVoDto $pathnameVoDto): RfcDto
    {
        $pathnameVo = $this->mapper->toEntity($pathnameVoDto);

        $rfc = $this->repository->findByPathname($pathnameVo);

        return $this->mapper->fromEntity($rfc);
    }

}
