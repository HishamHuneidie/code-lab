<?php

namespace Hisham\CodeLab\Common\Exception;

final readonly class Error
{
    public function __construct(
        public string $type,
        public string $detail,
    ) {}
}