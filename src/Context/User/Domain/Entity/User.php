<?php

namespace Hisham\CodeLab\Context\User\Domain\Entity;

use Hisham\CodeLab\Common\Util\GetterSetterTrait;
use Hisham\CodeLab\Common\ValueObject\IdVo;
use DateTime;

/**
 * Entity User. Used in repositories
 *
 * @method null|IdVo getId()
 * @method string getUsername()
 * @method string getEmail()
 * @method string getPassword()
 * @method UserStatus getStatus()
 * @method DateTime getCreatedAt()
 * @method User setId(?IdVo $id)
 * @method User setUsername(string $username)
 * @method User setEmail(string $email)
 * @method User setPassword(string $password)
 * @method User setStatus(UserStatus $status)
 * @method User setCreatedAt(DateTime $createdAt)
 */
class User
{
    use GetterSetterTrait;

    public function __construct(
        private ?IdVo      $id,
        private string     $username,
        private string     $email,
        private string     $password,
        private UserStatus $status,
        private DateTime   $createdAt,
    ) {}
}