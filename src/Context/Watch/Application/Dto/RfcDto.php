<?php

namespace Hisham\CodeLab\Context\Watch\Application\Dto;

/**
 * An RFC is a Request for comments
 */
final class RfcDto
{
    public function __construct(
        public EscapedPathnameVoDto $pathname,
        public string               $title,
        public string               $type,
        public string               $version,
        public string               $status,
        public string               $phpLink = '',
    ) {}

}
