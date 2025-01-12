<?php

namespace Hisham\CodeLab\Context\User\Infrastructure\Persistence\Repository;

use Hisham\CodeLab\Common\Util\UuidService;
use Hisham\CodeLab\Common\ValueObject\IdVo;
use Hisham\CodeLab\Context\User\Domain\Entity\User;
use Hisham\CodeLab\Context\User\Domain\Entity\UserStatus;
use Hisham\CodeLab\Context\User\Domain\Repository\UserRepositoryInterface;
use DateTime;

class UserRepository implements UserRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function find(): array
    {
        $user = new User(
            id       : UuidService::generate(),
            username : 'hish.nuevo',
            email    : 'hisham@nuevo.com',
            password : '123',
            status   : UserStatus::ACTIVE,
            createdAt: new DateTime(),
        );

        return [$user, $user];
    }

    /**
     * @inheritDoc
     */
    public function findById(IdVo $id): User
    {
        return new User(
            id       : UuidService::generate(),
            username : 'hish.nuevo',
            email    : 'hisham@nuevo.com',
            password : '123',
            status   : UserStatus::ACTIVE,
            createdAt: new DateTime(),
        );
    }

    /**
     * @inheritDoc
     */
    public function save(User $user): void
    {
        // TODO: Implement save() method.
    }
}