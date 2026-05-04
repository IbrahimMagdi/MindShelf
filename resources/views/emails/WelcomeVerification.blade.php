@extends('emails.layout')

@section('title', 'Welcome to MindShelf')
@section('heading', 'Welcome to MindShelf! 🎉')
@section('subheading', 'Activate your account to get started.')

@section('content')
    <p style="margin:0 0 16px; font-size:14px; color:#444; line-height:1.6; text-align:center;">
        Hi {{ $userName }},<br><br>
        Welcome aboard! Your personal digital library is ready.<br>
        To activate your account, use the code below:
    </p>

    <div style="text-align:center; margin:32px 0;">
        <div style="display:inline-block; background:#f3f4f6; padding:24px 48px; border-radius:12px; border:2px dashed #8b6914;">
            <span style="font-size:36px; font-weight:bold; color:#8b6914; letter-spacing:8px;">{{ $code }}</span>
        </div>
        <p style="margin:16px 0 0; font-size:12px; color:#666;">
            ⏰ Expires in 60 minutes
        </p>
    </div>

    <p style="margin:0; font-size:13px; color:#666; text-align:center;">
        If you didn't create this account, please ignore this email.
    </p>
@endsection

@section('security_title', 'Need help?')
@section('security_info')
    If you have any questions, contact our support team at support@mindshelf.com
@endsection
