<?php

namespace Hisham\CodeLab\Context\Watch\Domain\Entity;

use Hisham\CodeLab\Common\ValueObject\AbstractValueObject;
use Hisham\CodeLab\Common\ValueObject\ValueObjectException;
use Hisham\CodeLab\Common\ValueObject\ValueObjectInterface;

/**
 * Pathname that could have slashes
 *
 * @method string getValue()
 * @extends AbstractValueObject<string, PathnameVo>
 */
final class PathnameVo extends AbstractValueObject
{
    public function __construct(
        public readonly string $value,
    )
    {
        $this->validations();
    }

    public function equals(ValueObjectInterface $object): bool
    {
        return $object instanceof PathnameVo
            && $this->value === $object->getValue();
    }

    /**
     * Add validations on creation
     *
     * @return void
     */
    private function validations(): void {}

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
