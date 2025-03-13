<?php

namespace Hisham\CodeLab\Context\Watch\Application\Dto;

/**
 * PHP Version
 */
final class VersionDto
{
    public function __construct(
        public string       $versionNumber,
        public string       $link,
        public string       $status,
        public ReleaseVoDto $release,
    ) {}

}
