<?php

namespace Hisham\CodeLab\Context\Watch\Application\Mapper;

use Hisham\CodeLab\Common\Mapper\DtoMapperException;
use Hisham\CodeLab\Component\Attribute\DtoMapper;
use Hisham\CodeLab\Component\Mapper\AbstractDtoMapper;
use Hisham\CodeLab\Component\Mapper\DtoMapperInterface;
use Hisham\CodeLab\Context\Watch\Application\Dto\RfcDto;
use Hisham\CodeLab\Context\Watch\Domain\Entity\Rfc;
use Throwable;

/**
 * @implements DtoMapperInterface<RfcDto, Rfc>
 */
#[DtoMapper(RfcDto::class, Rfc::class)]
final class RfcDtoMapper extends AbstractDtoMapper
{
    /**
     * @inheritDoc
     */
    public function toEntity(object $dto): object
    {
        try {
            $entity = new Rfc(
                pathname: $this->mapper->toEntity($dto->pathname),
                title   : $dto->title,
                type    : $dto->type,
                version : $dto->version,
                status  : $dto->status,
                phpLink : $dto->phpLink,
            );
        } catch (Throwable $e) {
            throw new DtoMapperException('Error mapping RfcDto -> Rfc');
        }

        return $entity;
    }

    /**
     * @inheritDoc
     */
    public function fromEntity(object $entity): object
    {
        try {
            $dto = new RfcDto(
                pathname: $this->mapper->fromEntity($entity->getPathname()),
                title   : $entity->getTitle(),
                type    : $entity->getType(),
                version : $entity->getVersion(),
                status  : $entity->getStatus(),
                phpLink : $entity->getPhpLink(),
            );
        } catch (Throwable $e) {
            throw new DtoMapperException('Error mapping Rfc -> RfcDto');
        }

        return $dto;
    }

}
