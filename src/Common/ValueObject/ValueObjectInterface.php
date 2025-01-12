<?php

namespace Hisham\CodeLab\Common\ValueObject;

interface ValueObjectInterface
{
    /**
     * Verify if the current ValueObject is equal to other ValueObject
     *
     * @param self $object The object to compare with
     *
     * @return bool Returns true if both objects are equals
     */
    public function equals(self $object): bool;

}
