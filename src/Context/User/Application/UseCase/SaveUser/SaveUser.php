<?php

namespace Hisham\CodeLab\Context\User\Application\UseCase\SaveUser;

use Hisham\CodeLab\Common\Mapper\MapperException;
use Hisham\CodeLab\Common\Repository\RepositoryException;
use Hisham\CodeLab\Common\ValueObject\IdVo;
use Hisham\CodeLab\Common\ValueObject\ValueObjectException;
use Hisham\CodeLab\Component\Mapper\MapperInterface;
use Hisham\CodeLab\Context\User\Application\Mapper\UserMapper;
use Hisham\CodeLab\Context\User\Domain\Repository\UserRepositoryInterface;
use Hisham\CodeLab\Context\User\Infrastructure\Persistence\Repository\UserRepository;

/**
 * UserCase to create/update user
 */
final readonly class SaveUser
{
    /**
     * @param UserRepository $repository
     * @param UserMapper     $mapper
     */
    public function __construct(
        private UserRepositoryInterface $repository,
        private MapperInterface         $mapper,
    ) {}

    /**
     * @param SaveUserCommand $command
     *
     * @return void
     * @throws MapperException|RepositoryException|ValueObjectException
     */
    public function execute(SaveUserCommand $command): void
    {
        $user = $this->mapper->toEntity($command->user);

        $id = !$command->id ? null : new IdVo($command->id);

        $user->setId($id);

        $this->repository->save($user);
    }
}