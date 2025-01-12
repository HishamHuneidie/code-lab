<?php

namespace Hisham\CodeLab\Context\Watch\Domain\Entity;

use Hisham\CodeLab\Common\Util\GetterSetterTrait;

/**
 * Additional info about releases
 *
 * Getters:
 * @method null|string getVersionNumber()
 * @method null|string getLink()
 * @method null|string getDate()
 * @method null|string getListLink()
 */
final readonly class ReleaseVo
{
    use GetterSetterTrait;

    public function __construct(
        private ?string $versionNumber = null,
        private ?string $link = null,
        private ?string $date = null,
        private ?string $listLink = null,
    ) {}

}
