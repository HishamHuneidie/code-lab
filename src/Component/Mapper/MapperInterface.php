<?php

namespace Hisham\CodeLab\Component\Mapper;

use Hisham\CodeLab\Common\Mapper\MapperException;

/**
 * Contract for objects that map Dto and entities
 *
 * @psalm-template TDto of object
 * @psalm-template TEntity of object
 */
interface MapperInterface
{
    /**
     * Create an entity from a Dto
     *
     * @psalm-param TDto $dto
     * @psalm-return TEntity
     * @throws MapperException
     */
    public function toEntity(object $dto): object;

    /**
     * Create a Dto from an entity
     *
     * @psalm-param TEntity $entity
     * @psalm-return TDto
     * @throws MapperException
     */
    public function fromEntity(object $entity): object;

    /**
     * Create an entity list from a Dto list
     *
     * @psalm-param TDto[] $dto
     * @psalm-return TEntity[]
     * @throws MapperException
     */
    public function toEntityList(array $dtoList): array;

    /**
     * Create a Dto list from an entity list
     *
     * @psalm-param TEntity[] $entity
     * @psalm-return TDto[]
     * @throws MapperException
     */
    public function fromEntityList(array $entityList): array;
}