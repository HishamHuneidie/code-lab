<?php

namespace Hisham\CodeLab\Context\User\Domain\Entity;

use Hisham\CodeLab\Common\Util\EnumTrait;

enum UserStatus: string
{
    use EnumTrait;

    case ACTIVE  = 'ACTIVE';
    case BLOCKED = 'BLOCKED';
    case DELETED = 'DELETED';
}
