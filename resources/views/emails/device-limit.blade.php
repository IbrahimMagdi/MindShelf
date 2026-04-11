@extends('emails.layout')

@section('title', 'Verification Code')
@section('heading', 'Your Signup Verification Code')

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

    <p style="text-align:center; margin:0; font-size:13px; color:#999; font-weight:500;">
        🔒 Don't share this code with anyone!
    </p>
@endsection

@section('security_title', 'Was this request not made by you?')
@section('security_info')
    This code was generated from a request made using <strong style="color:#5c4a3d;">{{ $browser }}</strong>
    on <strong style="color:#5c4a3d;">{{ $platform }}</strong> with IP <strong style="color:#5c4a3d;">{{ $ip }}</strong>.
    If you did not initiate this request, you can safely ignore this email.
@endsection

@section('footer_badge')
    ● IP {{ $ip }}
@endsection
