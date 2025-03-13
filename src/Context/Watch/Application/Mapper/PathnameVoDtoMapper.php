<?php

namespace Hisham\CodeLab\Context\Watch\Application\Mapper;

use Hisham\CodeLab\Common\Mapper\DtoMapperException;
use Hisham\CodeLab\Component\Attribute\DtoMapper;
use Hisham\CodeLab\Component\Mapper\AbstractDtoMapper;
use Hisham\CodeLab\Component\Mapper\DtoMapperInterface;
use Hisham\CodeLab\Context\Watch\Application\Dto\EscapedPathnameVoDto;
use Hisham\CodeLab\Context\Watch\Domain\Entity\PathnameVo;
use Throwable;

/**
 * @implements DtoMapperInterface<EscapedPathnameVoDto, PathnameVo>
 */
#[DtoMapper(EscapedPathnameVoDto::class, PathnameVo::class)]
final class PathnameVoDtoMapper extends AbstractDtoMapper
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
            throw new DtoMapperException('Error mapping PathnameVoDto -> PathnameVo');
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
            throw new DtoMapperException('Error mapping PathnameVo -> PathnameVoDto');
        }

        return $dto;
    }

}
