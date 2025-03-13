<?php

namespace Hisham\CodeLab\Context\Watch\Application\Mapper;

use Hisham\CodeLab\Common\Mapper\DtoMapperException;
use Hisham\CodeLab\Component\Attribute\DtoMapper;
use Hisham\CodeLab\Component\Mapper\AbstractDtoMapper;
use Hisham\CodeLab\Component\Mapper\DtoMapperInterface;
use Hisham\CodeLab\Context\Watch\Application\Dto\ReleaseVoDto;
use Hisham\CodeLab\Context\Watch\Application\Dto\VersionDto;
use Hisham\CodeLab\Context\Watch\Domain\Entity\ReleaseVo;
use Hisham\CodeLab\Context\Watch\Domain\Entity\Version;
use Throwable;

/**
 * @implements DtoMapperInterface<VersionDto, Version>
 */
#[DtoMapper(VersionDto::class, Version::class)]
class VersionDtoMapper extends AbstractDtoMapper
{
    /**
     * @inheritDoc
     */
    public function toEntity(object $dto): object
    {
        try {
            $entity = new Version(
                versionNumber: $dto->versionNumber,
                link         : $dto->link,
                status       : $dto->status,
                release      : new ReleaseVo(
                    versionNumber: $dto->release->versionNumber,
                    link         : $dto->release->link,
                    date         : $dto->release->date,
                    listLink     : $dto->release->listLink,
                ),
            );
        } catch (Throwable $e) {
            throw new DtoMapperException('Error mapping VersionDto -> Version');
        }

        return $entity;
    }

    /**
     * @inheritDoc
     */
    public function fromEntity(object $entity): object
    {
        try {
            $dto = new VersionDto(
                versionNumber: $entity->getVersionNumber(),
                link         : $entity->getLink(),
                status       : $entity->getStatus(),
                release      : new ReleaseVoDto(
                    versionNumber: $entity->getRelease()->getVersionNumber(),
                    link         : $entity->getRelease()->getLink(),
                    date         : $entity->getRelease()->getDate(),
                    listLink     : $entity->getRelease()->getListLink(),
                ),
            );
        } catch (Throwable $e) {
            throw new DtoMapperException('Error mapping Version -> VersionDto');
        }

        return $dto;
    }

}
