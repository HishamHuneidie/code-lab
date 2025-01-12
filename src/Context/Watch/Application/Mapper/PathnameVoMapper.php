<?php

namespace Hisham\CodeLab\Context\Watch\Application\Mapper;

use Hisham\CodeLab\Common\Mapper\MapperException;
use Hisham\CodeLab\Component\Attribute\Mapper;
use Hisham\CodeLab\Component\Mapper\AbstractMapper;
use Hisham\CodeLab\Component\Mapper\MapperInterface;
use Hisham\CodeLab\Context\Watch\Application\Dto\EscapedPathnameVoDto;
use Hisham\CodeLab\Context\Watch\Domain\Entity\PathnameVo;
use Throwable;

/**
 * @implements MapperInterface<EscapedPathnameVoDto, PathnameVo>
 */
#[Mapper(EscapedPathnameVoDto::class, PathnameVo::class)]
final class PathnameVoMapper extends AbstractMapper
{
    private const SLASH_ESCAPED = '---';
    private const SLASH_NORMAL  = '/';

    /**
     * @inheritDoc
     */
    public function toEntity(object $dto): object
    {
        $normalPathname = str_replace(self::SLASH_ESCAPED, self::SLASH_NORMAL, $dto->getValue());

        try {
            $entity = PathnameVo::create($normalPathname);
        } catch (Throwable $e) {
            throw new MapperException('Error mapping PathnameVoDto -> PathnameVo');
        }

        return $entity;
    }

    /**
     * @inheritDoc
     */
    public function fromEntity(object $entity): object
    {
        $escapedPathname = str_replace(self::SLASH_NORMAL, self::SLASH_ESCAPED, $entity->getValue());

        try {
            $dto = EscapedPathnameVoDto::create($escapedPathname);
        } catch (Throwable $e) {
            throw new MapperException('Error mapping PathnameVo -> PathnameVoDto');
        }

        return $dto;
    }

}
