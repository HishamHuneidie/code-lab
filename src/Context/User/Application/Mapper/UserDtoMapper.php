<?php

namespace Hisham\CodeLab\Context\User\Application\Mapper;

use DateTime;
use Hisham\CodeLab\Common\Mapper\DtoMapperException;
use Hisham\CodeLab\Common\Util\GlobalConfig;
use Hisham\CodeLab\Common\ValueObject\IdVo;
use Hisham\CodeLab\Component\Attribute\DtoMapper;
use Hisham\CodeLab\Component\Mapper\AbstractDtoMapper;
use Hisham\CodeLab\Component\Mapper\DtoMapperInterface;
use Hisham\CodeLab\Context\User\Application\Dto\UserDto;
use Hisham\CodeLab\Context\User\Domain\Entity\User;
use Hisham\CodeLab\Context\User\Domain\Entity\UserStatus;
use Throwable;

/**
 * @implements DtoMapperInterface<UserDto, User>
 */
#[DtoMapper(UserDto::class, User::class)]
final class UserDtoMapper extends AbstractDtoMapper
{
    /**
     * @inheritDoc
     */
    public function toEntity(object $dto): object
    {
        try {
            $entity = new User(
                id       : new IdVo($dto->id),
                username : $dto->username,
                email    : $dto->email,
                password : $dto->password,
                status   : UserStatus::fromName($dto->status),
                createdAt: !$dto->createdAt
                    ? null
                    : new DateTime($dto->createdAt),
            );
        } catch (Throwable $e) {
            throw new DtoMapperException('Error mapping UserDto -> User');
        }

        return $entity;
    }

    /**
     * @inheritDoc
     */
    public function fromEntity(object $entity): object
    {
        try {
            $dto = new UserDto(
                id       : $entity->getId()->getValue(),
                username : $entity->getUsername(),
                email    : $entity->getEmail(),
                password : $entity->getPassword(),
                status   : $entity->getStatus()->value,
                createdAt: $entity->getCreatedAt() instanceof DateTime
                    ? $entity->getCreatedAt()->format(GlobalConfig::DATE_FORMAT_DEFAULT)
                    : null,
            );
        } catch (Throwable $e) {
            throw new DtoMapperException('Error mapping User -> UserDto');
        }

        return $dto;
    }
}