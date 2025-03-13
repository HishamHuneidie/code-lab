<?php

namespace Hisham\CodeLab\Context\Watch\Application\Dto;

use Hisham\CodeLab\Common\ValueObject\AbstractValueObject;
use Hisham\CodeLab\Common\ValueObject\ValueObjectException;
use Hisham\CodeLab\Common\ValueObject\ValueObjectInterface;

/**
 * Pathname that escapes the slashes
 *
 * @method string getValue()
 * @extends AbstractValueObject<string, EscapedPathnameVoDto>
 */
final class EscapedPathnameVoDto extends AbstractValueObject
{
    /**
     * @param string $value
     *
     * @throws ValueObjectException
     */
    public function __construct(
        public readonly string $value,
    )
    {
        $this->validations();
    }

    public function equals(ValueObjectInterface $object): bool
    {
        return $object instanceof EscapedPathnameVoDto
            && $this->value === $object->getValue();
    }

    /**
     * Add validations on creation
     *
     * @return void
     * @throws ValueObjectException
     */
    private function validations(): void
    {
        if (str_contains($this->value, "/")) {
            throw new ValueObjectException('This pathname cannot have slashes (/)');
        }
    }

    /**
     * Create a new instance of a value object
     *
     * @param string $value
     *
     * @return ValueObjectInterface
     * @throws ValueObjectException
     */
    public static function create(string $value): object
    {
        return new self($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }

}
