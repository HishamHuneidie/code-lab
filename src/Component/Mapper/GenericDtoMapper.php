<?php

namespace Hisham\CodeLab\Component\Mapper;

use Hisham\CodeLab\Common\Mapper\DtoMapperException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Stopwatch\Stopwatch;

class GenericDtoMapper implements DtoMapperInterface
{
    /**
     * @var DtoMapperInterface[]
     */
    private array $mappers = [];

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly Stopwatch       $stopwatch,
    ) {}

    /**
     * @inheritDoc
     */
    public function toEntity(object $dto): object
    {
        $this->stopwatch->start(DtoMapperInterface::class);
        $entity = $this->resolve($dto)->toEntity($dto);
        $this->stopwatch->stop(DtoMapperInterface::class);

        return $entity;
    }

    /**
     * @inheritDoc
     */
    public function fromEntity(object $entity): object
    {
        $this->stopwatch->start(DtoMapperInterface::class);
        $dto = $this->resolve($entity)->fromEntity($entity);
        $this->stopwatch->stop(DtoMapperInterface::class);

        return $dto;
    }

    /**
     * @inheritDoc
     */
    public function toEntityList(array $dtoList): array
    {
        if (empty($dtoList)) {
            return [];
        }

        $this->stopwatch->start(DtoMapperInterface::class);

        $mapper = $this->resolve($dtoList[0]);
        $entityList = array_map(
            fn(object $dto) => $mapper->toEntity($dto),
            $dtoList,
        );

        $this->stopwatch->stop(DtoMapperInterface::class);

        return $entityList;
    }

    /**
     * @inheritDoc
     */
    public function fromEntityList(array $entityList): array
    {
        if (empty($entityList)) {
            return [];
        }

        $this->stopwatch->start(DtoMapperInterface::class);

        $mapper = $this->resolve($entityList[0]);
        $dtoList = array_map(
            fn(object $entity) => $mapper->fromEntity($entity),
            $entityList,
        );

        $this->stopwatch->stop(DtoMapperInterface::class);

        return $dtoList;
    }

    /**
     * Resolve specific mapper
     *
     * @param object $object
     *
     * @return DtoMapperInterface
     * @throws DtoMapperException
     */
    private function resolve(object $object): DtoMapperInterface
    {
        $class = get_class($object);
        $mapper = $this->mappers[$class] ?? $this->mappers['*'] ?? null;

        if (!$mapper) {
            throw new DtoMapperException("No mapper found for {$class}");
        }

        $this->logger->debug(
            sprintf('Map "%s" with "%s"', $class, get_class($mapper)),
        );

        return $mapper;
    }

    /**
     * Add a mapper for a specific Dto or Entity
     * This method is called by one CompilerPass
     *
     * @param string             $objectClass
     * @param DtoMapperInterface $mapper
     *
     * @return $this
     */
    public function addMapper(string $objectClass, DtoMapperInterface $mapper): self
    {
        if ($mapper instanceof AbstractDtoMapper) {
            $mapper->setGenericMapper($this);
        }

        $this->mappers[$objectClass] = $mapper;

        return $this;
    }
}