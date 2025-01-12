<?php

namespace Hisham\CodeLab\Common\Exception;

use Exception;
use Throwable;

class CommonException extends Exception
{
    /**
     * @param string         $message
     * @param Error[]        $errors
     * @param Throwable|null $previous
     */
    public function __construct(
        string        $message = '',
        // TODO: It's necessary to solve this
        private array $errors = [],
        ?Throwable    $previous = null,
    )
    {
        parent::__construct($message, previous: $previous);
    }
}