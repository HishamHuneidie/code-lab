<?php

namespace Hisham\CodeLab\Common\ValueObject;

use Hisham\CodeLab\Common\Util\GetterSetterTrait;

abstract class AbstractValueObject implements ValueObjectInterface
{
    use GetterSetterTrait;
}
