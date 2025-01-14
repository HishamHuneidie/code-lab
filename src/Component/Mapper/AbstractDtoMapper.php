<?php

namespace Hisham\CodeLab\Component\Mapper;

use Exception;
use Hisham\CodeLab\Common\Mapper\DtoMapperException;
use Hisham\CodeLab\Context\User\Application\Dto\UserDto;
use Hisham\CodeLab\Context\User\Domain\Entity\User;

/**
 * @implements DtoMapperInterface<UserDto, User>
 */
abstract class AbstractDtoMapper implements DtoMapperInterface
{
    protected DtoMapperInterface $mapper;

    /**
     * @inheritDoc
     */
    public function toEntityList(array $dtoList): array
    {
        try {
            $entityList = array_map(fn(object $dto) => $this->toEntity($dto), $dtoList);
        } catch (Exception $e) {
            $className = get_class($this);
            throw new DtoMapperException("Error mapping Dto[] -> Entity[] in {$className}");
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
            throw new DtoMapperException("Error mapping Entity[] -> Dto[] in {$className}");
        }

        return $dtoList;
    }

    /**
     * Set a generic mapper
     *
     * @param DtoMapperInterface $mapper
     *
     * @return void
     */
    public function setGenericMapper(DtoMapperInterface $mapper): void
    {
        $this->mapper = $mapper;
    }
}