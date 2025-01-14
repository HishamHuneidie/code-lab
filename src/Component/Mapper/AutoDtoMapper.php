<?php

namespace Hisham\CodeLab\Component\Mapper;

use Hisham\CodeLab\Common\Mapper\DtoMapperException;
use Hisham\CodeLab\Component\Attribute\DtoMapper;
use ReflectionClass;
use ReflectionException;

/**
 * @implements DtoMapperInterface
 */
#[DtoMapper('*', '*')]
final class AutoDtoMapper extends AbstractDtoMapper
{
    private array $cachedDtoClasses;
    private array $cachedEntityClasses;
    private static array $DTO_CANDIDATES = [
        'Hisham\CodeLab\Context\{context}\Application\Dto\{shortname}Dto',
        'Hisham\CodeLab\Context\{context}\Application\Dto\View\{shortname}Dto',
        'Hisham\CodeLab\Context\{context}\Application\Dto\{subdomain}\{shortname}Dto',
    ];
    private static array $ENTITY_CANDIDATES = [
        'Hisham\CodeLab\Context\{context}\Domain\Entity\{shortname}',
        'Hisham\CodeLab\Context\{context}\Domain\Entity\View\{shortname}',
        'Hisham\CodeLab\Context\{context}\Domain\Entity\{subdomain}\{shortname}',
    ];

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function toEntity(object $dto): object
    {
        $dtoClass = get_class($dto);
        $entityClass = $this->findEntityClass($dtoClass);
        if (!$entityClass) throw new DtoMapperException(`Cannot find Entity to map '{$dtoClass}'`);

        $entity = (new ReflectionClass($entityClass))->newInstanceWithoutConstructor();
        foreach (get_object_vars($dto) as $propertyName => $propertyValue) {
            if (!property_exists($entity, $propertyName)) continue;

            $entity->{$propertyName} = $propertyValue;
        }

        return $entity;
    }

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function fromEntity(object $entity): object
    {
        $entityClass = get_class($entity);
        $dtoClass = $this->findDtoClass($entityClass);
        if (!$dtoClass) throw new DtoMapperException(`Cannot find Dto to map '{$entityClass}'`);

        $dto = (new ReflectionClass($dtoClass))->newInstanceWithoutConstructor();
        $reflectedEntity = new ReflectionClass($entityClass);

        foreach ($reflectedEntity->getProperties() as $property) {
            $property->setAccessible(true);
            $propertyName = $property->getName();

            if (!property_exists($dto, $propertyName)) continue;

            $dto->{$propertyName} = $property->getValue($entity);
        }

        return $dto;
    }

    /**
     * @psalm-param class-string $className
     *
     * @return string|null
     */
    public function getContext(string $className): ?string
    {
        $classNameParts = explode('\\', $className);

        $context = $classNameParts[2] ?? null;

        return match (true) {
            !$context             => null,
            $context === 'Shared' => "{$context}\\{$classNameParts[3]}",
            default               => $context,
        };
    }

    /**
     * @param string $entityClass
     *
     * @return string|null
     * @throws ReflectionException
     * @throws DtoMapperException
     */
    private function findDtoClass(string $entityClass): ?string
    {
        if ($dtoClass = $this->cachedDtoClasses[$entityClass] ?? null) {
            return $dtoClass;
        }

        $context = $this->getContext($entityClass);
        if (!$context) throw new DtoMapperException(`Cannot find domain of '{$entityClass}'`);

        $shortName = (new ReflectionClass($entityClass))->getShortName();

        foreach (self::$DTO_CANDIDATES as $dtoCandidate) {
            $subDomain = str_replace('View', '', $shortName);

            $dtoClass = str_replace(
                ['{context}', '{subdomain}', '{shortname}'],
                [$context, $subDomain, $shortName],
                $dtoCandidate,
            );

            if (class_exists($dtoClass)) {
                return $this->cachedDtoClasses[$entityClass] = $dtoClass;
            }
        }

        return null;
    }

    /**
     * @param string $dtoClass
     *
     * @return string|null
     * @throws ReflectionException
     * @throws DtoMapperException
     */
    private function findEntityClass(string $dtoClass): ?string
    {
        if ($entityClass = $this->cachedEntityClasses[$dtoClass] ?? null) {
            return $entityClass;
        }

        $context = $this->getContext($dtoClass);
        if (!$context) throw new DtoMapperException(`Cannot find domain of '{$dtoClass}'`);

        $shortName = (new ReflectionClass($dtoClass))->getShortName();
        if (str_ends_with($shortName, 'Dto')) {
            $shortName = substr($shortName, 0, strlen($shortName) - 3);
        }

        foreach (self::$ENTITY_CANDIDATES as $dtoCandidate) {
            $subDomain = str_replace('View', '', $shortName);

            $entityClass = str_replace(
                ['{context}', '{subdomain}', '{shortname}'],
                [$context, $subDomain, $shortName],
                $dtoCandidate,
            );

            if (class_exists($entityClass)) {
                return $this->cachedEntityClasses[$dtoClass] = $entityClass;
            }
        }

        return null;
    }

}