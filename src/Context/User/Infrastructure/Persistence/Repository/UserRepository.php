<?php

namespace Hisham\CodeLab\Context\User\Infrastructure\Persistence\Repository;

use DateTime;
use Hisham\CodeLab\Common\Enum\MariaDbTable;
use Hisham\CodeLab\Common\Repository\RepositoryException;
use Hisham\CodeLab\Common\Util\UuidService;
use Hisham\CodeLab\Common\ValueObject\IdVo;
use Hisham\CodeLab\Component\Repository\MariaDB\AbstractMariaDbRepository;
use Hisham\CodeLab\Component\Repository\MariaDB\CommandBuilder;
use Hisham\CodeLab\Component\Repository\MariaDB\QueryBuilder;
use Hisham\CodeLab\Component\Repository\MariaDB\QueryCondition;
use Hisham\CodeLab\Context\User\Domain\Entity\User;
use Hisham\CodeLab\Context\User\Domain\Entity\UserStatus;
use Hisham\CodeLab\Context\User\Domain\Repository\UserRepositoryInterface;
use Throwable;

class UserRepository
    extends AbstractMariaDbRepository
    implements UserRepositoryInterface
{
    private MariaDbTable $table = MariaDbTable::USERS;

    /**
     * @inheritDoc
     */
    public function find(): array
    {
        $queryBuilder = new QueryBuilder($this->table, User::newEmpty());

        $arrayUsers = $this->select($queryBuilder);

        try {
            $users = array_map(
                function (array $user) {
                    return new User(
                        id       : IdVo::create($user['id']),
                        username : $user['username'],
                        email    : $user['email'],
                        password : $user['password'],
                        status   : UserStatus::from($user['status']),
                        createdAt: new DateTime($user['created_at']),
                    );
                },
                $arrayUsers,
            );
        } catch (Throwable $e) {
            throw new RepositoryException('Error mapping users in Repository');
        }

        return $users;
    }

    /**
     * @inheritDoc
     */
    public function findById(IdVo $id): User
    {
        $queryBuilder = (new QueryBuilder($this->table, User::newEmpty()))
            ->where(new QueryCondition('id', '=', $id->getValue()));

        $arrayUser = $this->selectOne($queryBuilder);

        if (!$arrayUser) {
            throw new RepositoryException('User not found');
        }

        try {
            $user = new User(
                id       : IdVo::create($arrayUser['id']),
                username : $arrayUser['username'],
                email    : $arrayUser['email'],
                password : $arrayUser['password'],
                status   : UserStatus::from($arrayUser['status']),
                createdAt: new DateTime($arrayUser['created_at']),
            );
        } catch (Throwable $e) {
            throw new RepositoryException('Error mapping user in Repository');
        }

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function save(User $user): void
    {
        try {

            if (!$user->getId()) {
                $user->setId(UuidService::generate());
            }

            $commandBuilder = new CommandBuilder($this->table, $user);

            $this->commandSave($commandBuilder);
        } catch (Throwable $e) {
            throw new RepositoryException('Error saving user in Repository');
        }
    }

}
