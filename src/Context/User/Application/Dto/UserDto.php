<?php

namespace Hisham\CodeLab\Context\User\Application\Dto;

/**
 * Dto User. Used in presentation and application layers
 */
final readonly class UserDto
{
    public function __construct(
        public ?string $id = null,
        public string  $username,
        public string  $email,
        public string  $password,
        public string  $status,
        public ?string $createdAt = null,
    ) {}
}