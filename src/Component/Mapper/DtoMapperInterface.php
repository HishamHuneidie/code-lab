<?php

namespace Hisham\CodeLab\Component\Mapper;

use Hisham\CodeLab\Common\Mapper\DtoMapperException;

/**
 * Contract for objects that map Dto and entities
 *
 * @psalm-template TDto of object
 * @psalm-template TEntity of object
 */
interface DtoMapperInterface
{
    /**
     * Create an entity from a Dto
     *
     * @psalm-param TDto $dto
     * @psalm-return TEntity
     * @throws DtoMapperException
     */
    public function toEntity(object $dto): object;

    /**
     * Create a Dto from an entity
     *
     * @psalm-param TEntity $entity
     * @psalm-return TDto
     * @throws DtoMapperException
     */
    public function fromEntity(object $entity): object;

    /**
     * Create an entity list from a Dto list
     *
     * @psalm-param TDto[] $dto
     * @psalm-return TEntity[]
     * @throws DtoMapperException
     */
    public function toEntityList(array $dtoList): array;

    /**
     * Create a Dto list from an entity list
     *
     * @psalm-param TEntity[] $entity
     * @psalm-return TDto[]
     * @throws DtoMapperException
     */
    public function fromEntityList(array $entityList): array;
}