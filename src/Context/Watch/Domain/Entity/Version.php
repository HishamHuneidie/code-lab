<?php

namespace Hisham\CodeLab\Context\Watch\Domain\Entity;

use Hisham\CodeLab\Common\Util\GetterSetterTrait;

/**
 * PHP Version
 *
 * Getters:
 * @method string getVersionNumber()
 * @method string getLink()
 * @method string getStatus()
 * @method ReleaseVo getRelease()
 */
final readonly class Version
{
    use GetterSetterTrait;

    public function __construct(
        private string    $versionNumber,
        private string    $link,
        private string    $status,
        private ReleaseVo $release,
    ) {}

}
