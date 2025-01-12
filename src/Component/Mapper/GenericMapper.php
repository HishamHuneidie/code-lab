<?php

namespace Hisham\CodeLab\Component\Mapper;

use Hisham\CodeLab\Common\Mapper\MapperException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Stopwatch\Stopwatch;

class GenericMapper implements MapperInterface
{
    /**
     * @var MapperInterface[]
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
        $this->stopwatch->start(MapperInterface::class);
        $entity = $this->resolve($dto)->toEntity($dto);
        $this->stopwatch->stop(MapperInterface::class);

        return $entity;
    }

    /**
     * @inheritDoc
     */
    public function fromEntity(object $entity): object
    {
        $this->stopwatch->start(MapperInterface::class);
        $dto = $this->resolve($entity)->fromEntity($entity);
        $this->stopwatch->stop(MapperInterface::class);

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

        $this->stopwatch->start(MapperInterface::class);

        $mapper = $this->resolve($dtoList[0]);
        $entityList = array_map(
            fn(object $dto) => $mapper->toEntity($dto),
            $dtoList,
        );

        $this->stopwatch->stop(MapperInterface::class);

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

        $this->stopwatch->start(MapperInterface::class);

        $mapper = $this->resolve($entityList[0]);
        $dtoList = array_map(
            fn(object $entity) => $mapper->fromEntity($entity),
            $entityList,
        );

        $this->stopwatch->stop(MapperInterface::class);

        return $dtoList;
    }

    /**
     * Resolve specific mapper
     *
     * @param object $object
     *
     * @return MapperInterface
     * @throws MapperException
     */
    private function resolve(object $object): MapperInterface
    {
        $class = get_class($object);
        $mapper = $this->mappers[$class] ?? $this->mappers['*'] ?? null;

        if (!$mapper) {
            throw new MapperException("No mapper found for {$class}");
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
     * @param string          $objectClass
     * @param MapperInterface $mapper
     *
     * @return $this
     */
    public function addMapper(string $objectClass, MapperInterface $mapper): self
    {
        if ($mapper instanceof AbstractMapper) {
            $mapper->setGenericMapper($this);
        }

        $this->mappers[$objectClass] = $mapper;

        return $this;
    }
}