<?php
namespace App\Services\Settings;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class OtpService
{
    public function generate(User $user, string $type): string
    {
        $this->checkRateLimit($user, $type);
        $user->otps()->where('type', $type)->update(['used' => true]);
        $code = $this->generateSecureCode();
        $user->otps()->create([
            'user_id' => $user->id,
            'code' => Hash::make($code),
            'type' => $type,
            'expires_at' => now()->addMinutes(10),
        ]);
        return $code;
    }

    public function verify(User $user, string $type, string $providedCode): bool
    {
        $otp = $user->otps()->where('type', $type)
            ->where('expires_at', '>', now())->where('used', false)->latest()->first();
        if (!$otp) {
            return false;
        }
        if (!Hash::check($providedCode, $otp->code)) {
            return false;
        }
        $otp->update(['used' => true]);
        return true;
    }
    private function checkRateLimit(User $user, string $type): void
    {
        $recentCount = $user->otps()->where('type', $type)->where('created_at', '>', now()->subMinutes(5))->count();
        if ($recentCount >= 3) {
            throw new \RuntimeException('Maximum 3 OTP requests per 5 minutes');
        }

    }

    private function generateSecureCode(): string
    {
        return (string) random_int(100000, 999999);
    }
}
