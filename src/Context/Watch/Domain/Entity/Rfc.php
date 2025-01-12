<?php

namespace Hisham\CodeLab\Context\Watch\Domain\Entity;

use Hisham\CodeLab\Common\Util\GetterSetterTrait;

/**
 * An RFC is a Request for comments
 *
 * Getters:
 * @method PathnameVo getPathname()
 * @method string getTitle()
 * @method string getType()
 * @method string getVersion()
 * @method string getStatus()
 * @method PathnameVo getPhpLink()
 */
final readonly class Rfc
{
    use GetterSetterTrait;

    public function __construct(
        private PathnameVo $pathname,
        private string     $title,
        private string     $type,
        private string     $version,
        private string     $status,
        private string     $phpLink = '',
    ) {}

}
