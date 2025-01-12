<?php

namespace Hisham\CodeLab\Context\User\Application\UseCase\SaveUser;

use Hisham\CodeLab\Context\User\Application\Dto\UserDto;

/**
 * RequestCommand used to save a user
 */
final class SaveUserCommand
{
    public function __construct(
        public ?string $id,
        public UserDto $user,
    ) {}
}