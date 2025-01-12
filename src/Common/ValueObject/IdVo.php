<?php

namespace Hisham\CodeLab\Common\ValueObject;

use Hisham\CodeLab\Common\Util\UuidService;

/**
 * ID with a UUID format. This has 32 characters separated in 5 groups (8–4–4–4–12)
 *
 * @method string getValue()
 * @extends AbstractValueObject<string, IdVo>
 */
class IdVo extends AbstractValueObject
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

    /**
     * @inheritDoc
     */
    public function equals(ValueObjectInterface $object): bool
    {
        return $object instanceof IdVo
            && $this->value === $object->getValue();
    }

    /**
     * @return void
     * @throws ValueObjectException
     */
    private function validations(): void
    {
        if (!UuidService::isValid($this)) {
            throw new ValueObjectException('ID is a non-valid ID');
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