<?php
declare(strict_types=1);

namespace App\Domain\Otp\Repositories;

use App\Domain\Otp\Entities\OtpEntity;
use App\Domain\Otp\Enums\OtpType;
use App\Domain\Otp\ValueObjects\OtpCode;

interface OtpRepositoryInterface
{
    public function findValidForUser(int $userId, OtpType $type): ?OtpEntity;

    public function findByCode(int $userId, OtpCode $code, OtpType $type): ?OtpEntity;

    public function save(OtpEntity $otp): void;

    public function markUsed(OtpEntity $otp): void;

    public function invalidatePrevious(int $userId, OtpType $type): void;

    public function countActiveForUser(int $userId, OtpType $type): int;

    public function deleteExpired(\DateTimeImmutable $before): int;
    public function findLatestForUser(int $userId, OtpType $type): ?OtpEntity;
}
