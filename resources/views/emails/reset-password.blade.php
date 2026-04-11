@extends('emails.layout')

@section('title', 'Reset Password')
@section('heading', 'Reset Your Password')
@section('subheading', 'Use this code to create a new password for your account.')

@section('content')
    <!-- OTP Boxes -->
    <table cellpadding="0" cellspacing="0" align="center" style="margin:0 auto 24px; border-spacing:8px;">
        <tr>
            @foreach(str_split($code) as $digit)
                <td style="width:48px; height:56px; background:#fff; border:1.5px solid #e5e5e5; border-radius:12px; text-align:center; font-size:24px; font-weight:600; color:#1a1a1a; box-shadow:0 2px 8px rgba(0,0,0,0.04);">
                    {{ $digit }}
                </td>
            @endforeach
        </tr>
    </table>

    <p style="text-align:center; margin:0; font-size:13px; color:#999;">
        🔒 This code will expire in 10 minutes
    </p>
@endsection

@section('security_title', 'Didn\'t request this?')
@section('security_info')
    If you didn't request a password reset, you can safely ignore this email. Your password will remain unchanged.
@endsection
