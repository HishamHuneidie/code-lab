<?php

namespace Hisham\CodeLab\Context\User\Application\UseCase\SaveUser;

use Hisham\CodeLab\Common\Mapper\DtoMapperException;
use Hisham\CodeLab\Common\Repository\RepositoryException;
use Hisham\CodeLab\Common\ValueObject\ValueObjectException;
use Hisham\CodeLab\Component\Mapper\DtoMapperInterface;
use Hisham\CodeLab\Context\User\Application\Mapper\UserDtoMapper;
use Hisham\CodeLab\Context\User\Domain\Repository\UserRepositoryInterface;
use Hisham\CodeLab\Context\User\Infrastructure\Persistence\Repository\UserRepository;

/**
 * UserCase to create/update user
 */
final readonly class SaveUser
{
    /**
     * @param UserRepository $repository
     * @param UserDtoMapper  $mapper
     */
    public function __construct(
        private UserRepositoryInterface $repository,
        private DtoMapperInterface      $mapper,
    ) {}

    /**
     * @param SaveUserCommand $command
     *
     * @return void
     * @throws DtoMapperException|RepositoryException|ValueObjectException
     */
    public function execute(SaveUserCommand $command): void
    {
        $user = $this->mapper->toEntity($command->user);

        $this->repository->save($user);
    }
}