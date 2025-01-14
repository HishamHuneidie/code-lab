<?php

namespace Hisham\CodeLab\Context\User\Application\UseCase\UserList;

use Hisham\CodeLab\Common\Mapper\DtoMapperException;
use Hisham\CodeLab\Common\Repository\RepositoryException;
use Hisham\CodeLab\Component\Mapper\DtoMapperInterface;
use Hisham\CodeLab\Context\User\Application\Dto\UserDto;
use Hisham\CodeLab\Context\User\Application\Mapper\UserDtoMapper;
use Hisham\CodeLab\Context\User\Domain\Repository\UserRepositoryInterface;
use Hisham\CodeLab\Context\User\Infrastructure\Persistence\Repository\UserRepository;

/**
 * UseCase that search all users with no filters
 */
final readonly class UserList
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
     * @return UserDto[]
     * @throws RepositoryException|DtoMapperException
     */
    public function execute(): array
    {
        $userList = $this->userRepository->find();

        return $this->mapper->fromEntityList($userList);
    }
}

