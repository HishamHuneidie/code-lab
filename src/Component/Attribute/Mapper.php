<?php

namespace Hisham\CodeLab\Component\Attribute;

use Attribute;

/**
 * Attribute used to declare a Mapper between Dto and entities
 *
 * Required to automatic Dto resolution
 * Used by DependencyInjection container to discover mappers and its source => target types
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Mapper
{
    /**
     * @psalm-param class-string $dto
     * @psalm-param class-string $entity
     */
    public function __construct(
        public string $dto,
        public string $entity,
    ) {}

    /**
     * Checks if attribute is valid
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->isFallback() || $this->hasValidClases();
    }

    /**
     * Checks if both Dto and entity are valid
     *
     * @return bool
     */
    public function hasValidClases(): bool
    {
        return !empty($this->dto)
            && !empty($this->entity)
            && class_exists($this->dto)
            && class_exists($this->entity);
    }

    /**
     * Checks if mapper is fallback
     *
     * @return bool
     */
    public function isFallback(): bool
    {
        return $this->dto === '*' && $this->entity === '*';
    }
}