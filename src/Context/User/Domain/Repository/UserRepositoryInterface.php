<?php

namespace Hisham\CodeLab\Context\User\Domain\Repository;

use Hisham\CodeLab\Common\Repository\RepositoryException;
use Hisham\CodeLab\Common\ValueObject\IdVo;
use Hisham\CodeLab\Context\User\Domain\Entity\User;

/**
 * Manage user
 */
interface UserRepositoryInterface
{

    /**
     * Search all users with no filters
     *
     * @return User[]
     * @throws RepositoryException
     */
    public function find(): array;

    /**
     * Search a user by ID
     *
     * @param IdVo $id
     *
     * @return User
     * @throws RepositoryException
     */
    public function findById(IdVo $id): User;

    /**
     * Save or update user
     *
     * @param User $user
     *
     * @return void
     * @throws RepositoryException
     */
    public function save(User $user): void;
}