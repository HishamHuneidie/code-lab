<?php

namespace Hisham\CodeLab\Context\Watch\Application\Dto;

/**
 * Additional info about releases
 */
final class ReleaseVoDto
{
    public function __construct(
        public ?string $versionNumber = null,
        public ?string $link = null,
        public ?string $date = null,
        public ?string $listLink = null,
    ) {}

}
