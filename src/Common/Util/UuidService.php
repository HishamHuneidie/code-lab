<?php

namespace Hisham\CodeLab\Common\Util;

use Hisham\CodeLab\Common\ValueObject\IdVo;
use Hisham\CodeLab\Common\ValueObject\ValueObjectException;
use Ramsey\Uuid\Uuid;

class UuidService
{

    /**
     * Method that generates UUIDs
     *
     * @return IdVo
     * @throws ValueObjectException
     */
    public static function generate(): IdVo
    {
        return new IdVo(Uuid::uuid4()->toString());
    }

    /**
     * Check if ID is valid
     *
     * @param IdVo $id
     *
     * @return bool
     */
    public static function isValid(IdVo $id): bool
    {
        return Uuid::isValid($id->getValue());
    }

}
