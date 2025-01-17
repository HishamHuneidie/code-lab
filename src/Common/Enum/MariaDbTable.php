<?php

namespace Hisham\CodeLab\Common\Enum;

use Hisham\CodeLab\Common\Util\EnumTrait;

enum MariaDbTable: string
{
    use EnumTrait;

    case USERS = 'User';
}
