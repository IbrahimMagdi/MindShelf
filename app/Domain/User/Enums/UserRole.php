<?php
declare(strict_types=1);

namespace App\Domain\User\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case AUTHOR = 'author';
    case CUSTOMER = 'customer';
    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrator',
            self::AUTHOR => 'Author',
            self::CUSTOMER => 'Customer',
        };
    }

    public function canPublish(): bool
    {
        return in_array($this, [self::ADMIN, self::AUTHOR], true);
    }
}
