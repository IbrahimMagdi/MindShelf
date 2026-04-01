<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ResetPasswordService
{
    public function reset(array $data): array
    {
        $record = DB::table('password_reset_tokens')->where('email', $data['email'])->first();
        if (!$record) {
            return ['success' => false, 'message' => __('auth.invalid_reset_request')];
        }
        if (!Hash::check($data['token'], $record->token)) {
            return ['success' => false, 'message' => __('auth.invalid_or_expired_code')];
        }
        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            return [
                'success' => false,
                'message' => __('auth.expired_code')
            ];
        }
        $user = User::where('email', $data['email'])->firstOrFail();
        $user->update([
            'password' => Hash::make($data['password'])
        ]);
        DB::table('password_reset_tokens')->where('email', $data['email'])->delete();
        return ['success' => true, 'message' => __('auth.successReset')];
    }
}
