<?php

namespace Hisham\CodeLab\Component\Mapper;

use Hisham\CodeLab\Common\Mapper\MapperException;
use Hisham\CodeLab\Context\User\Application\Dto\UserDto;
use Hisham\CodeLab\Context\User\Domain\Entity\User;
use Exception;

/**
 * @implements MapperInterface<UserDto, User>
 */
abstract class AbstractMapper implements MapperInterface
{
    protected MapperInterface $mapper;

    /**
     * @inheritDoc
     */
    public function toEntityList(array $dtoList): array
    {
        try {
            $entityList = array_map(fn(object $dto) => $this->toEntity($dto), $dtoList);
        } catch (Exception $e) {
            $className = get_class($this);
            throw new MapperException("Error mapping Dto[] -> Entity[] in {$className}");
        }

        return $entityList;
    }

    /**
     * @inheritDoc
     */
    public function fromEntityList(array $entityList): array
    {
        try {
            $dtoList = array_map(fn(object $entity) => $this->fromEntity($entity), $entityList);
        } catch (Exception $e) {
            $className = get_class($this);
            throw new MapperException("Error mapping Entity[] -> Dto[] in {$className}");
        }

        return $dtoList;
    }

    /**
     * Set a generic mapper
     *
     * @param MapperInterface $mapper
     *
     * @return void
     */
    public function setGenericMapper(MapperInterface $mapper): void
    {
        $this->mapper = $mapper;
    }
}