<?php

namespace Hisham\CodeLab\Context\User\Application\UseCase\UserById;

use Hisham\CodeLab\Common\Mapper\MapperException;
use Hisham\CodeLab\Common\Repository\RepositoryException;
use Hisham\CodeLab\Common\ValueObject\IdVo;
use Hisham\CodeLab\Common\ValueObject\ValueObjectException;
use Hisham\CodeLab\Component\Mapper\MapperInterface;
use Hisham\CodeLab\Context\User\Application\Dto\UserDto;
use Hisham\CodeLab\Context\User\Application\Mapper\UserMapper;
use Hisham\CodeLab\Context\User\Domain\Repository\UserRepositoryInterface;
use Hisham\CodeLab\Context\User\Infrastructure\Persistence\Repository\UserRepository;

/**
 * UseCase that search one user by ID
 */
final readonly class UserById
{

    /**
     * @param UserRepository $userRepository
     * @param UserMapper     $mapper
     */
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private MapperInterface         $mapper,
    ) {}

    /**
     * @throws RepositoryException|ValueObjectException|MapperException
     */
    public function execute(string $id): UserDto
    {
        $user = $this->userRepository->findById(
            id: new IdVo($id),
        );

        return $this->mapper->fromEntity($user);
    }
}