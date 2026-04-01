<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordService
{
    public function reset(array $data): array
    {
        $record = DB::table('password_reset_tokens')->where('email', $data['email'])->first();
        if (!$record) {
            return ['success' => false, 'message' => 'طلب تغيير كلمة المرور غير صحيح'];
        }

        if (!Hash::check($data['token'], $record->token)) {
            return ['success' => false, 'message' => 'كود التحقق غير صحيح أو انتهت صلاحيته'];
        }

        $expires = 60;
        if (now()->parse($record->created_at)->addMinutes($expires)->isPast()) {
            return ['success' => false, 'message' => 'انتهت صلاحية كود التحقق'];
        }

        $user = User::where('email', $data['email'])->first();
        $user->update([
            'password' => Hash::make($data['password'])
        ]);

        DB::table('password_reset_tokens')->where('email', $data['email'])->delete();
        return ['success' => true, 'message' => 'تم تغيير كلمة المرور بنجاح'];
    }
}
