<?php

namespace Hisham\CodeLab\Context\User\Application\UseCase\UserById;

use Hisham\CodeLab\Common\Mapper\DtoMapperException;
use Hisham\CodeLab\Common\Repository\RepositoryException;
use Hisham\CodeLab\Common\ValueObject\IdVo;
use Hisham\CodeLab\Common\ValueObject\ValueObjectException;
use Hisham\CodeLab\Component\Mapper\DtoMapperInterface;
use Hisham\CodeLab\Context\User\Application\Dto\UserDto;
use Hisham\CodeLab\Context\User\Application\Mapper\UserDtoMapper;
use Hisham\CodeLab\Context\User\Domain\Repository\UserRepositoryInterface;
use Hisham\CodeLab\Context\User\Infrastructure\Persistence\Repository\UserRepository;

/**
 * UseCase that search one user by ID
 */
final readonly class UserById
{

    /**
     * @param UserRepository $userRepository
     * @param UserDtoMapper  $mapper
     */
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private DtoMapperInterface      $mapper,
    ) {}

    /**
     * @throws RepositoryException|ValueObjectException|DtoMapperException
     */
    public function execute(string $id): UserDto
    {
        $user = $this->userRepository->findById(
            id: new IdVo($id),
        );

        return $this->mapper->fromEntity($user);
    }
}