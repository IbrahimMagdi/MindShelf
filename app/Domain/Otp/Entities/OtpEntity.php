<?php
declare(strict_types=1);

namespace App\Domain\Otp\Entities;

use App\Domain\Otp\Enums\OtpType;
use App\Domain\Otp\Exceptions\OtpException;
use App\Domain\Otp\ValueObjects\OtpCode;

final class OtpEntity
{
    private int $attempts = 0;

    private function __construct(
        private ?int $id,
        private int $userId,
        private OtpCode $code,
        private OtpType $type,
        private \DateTimeImmutable $expiresAt,
        private bool $used = false,
        private ?\DateTimeImmutable $verifiedAt = null,
    ) {}

    // ========================
    // 🔥 Factories
    // ========================

    public static function create(int $userId, OtpType $type): self
    {
        $code = OtpCode::generate();
        $expiresAt = (new \DateTimeImmutable())->modify("+{$type->expiryMinutes()} minutes");

        return new self(
            id: null,
            userId: $userId,
            code: $code,
            type: $type,
            expiresAt: $expiresAt,
            used: false,
            verifiedAt: null,
        );
    }

    public static function fromPersistence(
        int $id,
        int $userId,
        OtpCode $code,
        OtpType $type,
        \DateTimeImmutable $expiresAt,
        bool $used = false,
        int $attempts = 0,
        ?\DateTimeImmutable $verifiedAt = null,
    ): self {
        $otp = new self($id, $userId, $code, $type, $expiresAt, $used, $verifiedAt);
        $otp->attempts = $attempts;
        return $otp;
    }

    // ========================
    // 🧠 Business Logic
    // ========================

    public function verify(OtpCode $inputCode): void
    {
        $this->ensureNotUsed();
        $this->ensureNotExpired();
        $this->ensureMaxAttemptsNotReached();
        $this->recordAttempt();

        if (!$this->code->equals($inputCode)) {
            throw OtpException::invalidCode();
        }

        $this->markAsVerified();
    }

    public function invalidate(): void
    {
        $this->used = true;
    }

    // ========================
    // 🛡️ Guards (Invariants)
    // ========================

    private function ensureNotUsed(): void
    {
        if ($this->used) {
            throw OtpException::alreadyUsed();
        }
    }

    private function ensureNotExpired(): void
    {
        if ($this->isExpired()) {
            throw OtpException::expired();
        }
    }

    private function ensureMaxAttemptsNotReached(): void
    {
        if ($this->attempts >= $this->type->maxAttempts()) {
            throw OtpException::maxAttemptsReached($this->type->maxAttempts());
        }
    }

    private function recordAttempt(): void
    {
        $this->attempts++;
    }

    private function markAsVerified(): void
    {
        $this->used = true;
        $this->verifiedAt = new \DateTimeImmutable();
    }

    // ========================
    // 🧠 Queries
    // ========================

    public function checkValidation(): void
    {
        if ($this->used) {
            throw OtpException::alreadyUsed();
        }

        if ($this->isExpired()) {
            throw OtpException::expired();
        }
    }

    public function isExpired(): bool
    {
        return new \DateTimeImmutable() > $this->expiresAt;
    }


    public function isValid(): bool
    {
        return !$this->used && !$this->isExpired();
    }

    public function remainingAttempts(): int
    {
        return max(0, $this->type->maxAttempts() - $this->attempts);
    }

    public function canResend(): bool
    {
        $halfExpiry = (int) ($this->type->expiryMinutes() / 2);
        $resendAfter = $this->expiresAt->modify("-{$halfExpiry} minutes");

        return new \DateTimeImmutable() > $resendAfter || $this->used;
    }

    // ========================
    // 🧾 Getters
    // ========================

    public function getId(): ?int { return $this->id; }
    public function getUserId(): int { return $this->userId; }
    public function getCode(): OtpCode { return $this->code; }
    public function getType(): OtpType { return $this->type; }
    public function getExpiresAt(): \DateTimeImmutable { return $this->expiresAt; }
    public function isUsed(): bool { return $this->used; }
    public function getAttempts(): int { return $this->attempts; }
    public function getVerifiedAt(): ?\DateTimeImmutable { return $this->verifiedAt; }

    public function __toString(): string
    {
        return sprintf(
            'Otp[%s|user:%d|type:%s|%s]',
            $this->id ?? 'NEW',
            $this->userId,
            $this->type->value,
            $this->code
        );
    }
}
