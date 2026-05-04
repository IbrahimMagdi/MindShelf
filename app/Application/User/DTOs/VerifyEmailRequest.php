<?php

declare(strict_types=1);

namespace App\Application\User\DTOs;

final readonly class VerifyEmailRequest
{
    public function __construct(public string $email, public string $code){}

    public static function fromArray(array $data):self
    {
        return new self(email: $data['email'],code: $data['code']);
    }
}
