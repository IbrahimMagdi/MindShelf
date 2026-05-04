<?php

namespace App\Application\User\DTOs;

final readonly class ResendVerifyOtpRequest
{
    public function __construct(public string $email){}
    public static function fromArray(array $data): self
    {
        return new self(email: $data['email']);
    }

}
