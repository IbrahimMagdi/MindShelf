<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Otp;

use App\Domain\Otp\Entities\OtpEntity;
use App\Domain\Otp\Enums\OtpType;
use App\Domain\Otp\Repositories\OtpRepositoryInterface;
use App\Domain\Otp\ValueObjects\OtpCode;
use App\Models\Otp as OtpModel;
use Illuminate\Support\Facades\Hash;

class OtpRepositoryImpl implements OtpRepositoryInterface
{
    public function findValidForUser(int $userId, OtpType $type): ?OtpEntity
    {
        $record = OtpModel::where('user_id', $userId)
            ->where('type', $type->value)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        return $record ? $this->toEntity($record) : null;
    }

    public function findByCode(int $userId, OtpCode $code, OtpType $type): ?OtpEntity
    {
        // نجيب كل الأكواد النشطة ونفحص الـ hash
        $records = OtpModel::where('user_id', $userId)
            ->where('type', $type->value)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->get();

        foreach ($records as $record) {
            if (Hash::check($code->value(), $record->code)) {
                return $this->toEntity($record);
            }
        }

        return null;
    }

    public function save(OtpEntity $otp): void
    {
        OtpModel::create([
            'user_id' => $otp->getUserId(),
            'code' => Hash::make($otp->getCode()->value()),
            'type' => $otp->getType()->value,
            'expires_at' => $otp->getExpiresAt(),
            'used' => $otp->isUsed(),
            'attempts' => $otp->getAttempts(),
        ]);
    }

    public function markUsed(OtpEntity $otp): void
    {
        OtpModel::where('id', $otp->getId())->update([
            'used' => true,
            'verified_at' => now(),
        ]);
    }

    public function invalidatePrevious(int $userId, OtpType $type): void
    {
        OtpModel::where('user_id', $userId)
            ->where('type', $type->value)
            ->where('used', false)
            ->update(['used' => true]);
    }

    public function countActiveForUser(int $userId, OtpType $type): int
    {
        return OtpModel::where('user_id', $userId)
            ->where('type', $type->value)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->count();
    }

    public function deleteExpired(\DateTimeImmutable $before): int
    {
        return OtpModel::where('expires_at', '<', $before)->delete();
    }

    private function toEntity(OtpModel $model): OtpEntity
    {
        $expiresAt = \DateTimeImmutable::createFromMutable($model->expires_at);

        $verifiedAt = null;
        if ($model->verified_at) {
            $verifiedAt = \DateTimeImmutable::createFromMutable($model->verified_at);
        }

        return OtpEntity::fromPersistence(
            id: (int) $model->id,
            userId: (int) $model->user_id,
            code: new OtpCode('000000'),
            type: OtpType::from($model->type),
            expiresAt: \DateTimeImmutable::createFromMutable($model->expires_at),
            used: (bool) $model->used,
            attempts: (int) ($model->attempts ?? 0),
            verifiedAt: $model->verified_at ? \DateTimeImmutable::createFromMutable($model->verified_at) : null,
        );
    }

    public function findLatestForUser(int $userId, OtpType $type): ?OtpEntity
    {
        $record = OtpModel::where('user_id', $userId)
            ->where('type', $type->value)->latest()->first();
        return $record ? $this->toEntity($record) : null;
    }
}
