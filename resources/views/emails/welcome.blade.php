@extends('emails.layout')

@section('title', 'Welcome to MindShelf')
@section('heading', 'Welcome to MindShelf! 🎉')
@section('subheading', 'Your personal digital library is ready.')

@section('content')
    <p style="margin:0 0 16px; font-size:14px; color:#444; line-height:1.6; text-align:center;">
        Hi {{ $userName }},<br><br>
        Welcome aboard! Start exploring our collection of books and build your own digital library.
    </p>

    <div style="text-align:center; margin:32px 0;">
        <a href="{{ $storeUrl }}" style="display:inline-block; background:#8b6914; color:#fff; padding:14px 32px; border-radius:12px; text-decoration:none; font-weight:600; font-size:14px; box-shadow:0 4px 12px rgba(139, 105, 20, 0.3);">
            Browse Book Store
        </a>
    </div>

    <p style="margin:0; font-size:13px; color:#666; text-align:center;">
        Discover thousands of books waiting for you!
    </p>
@endsection

@section('security_title', 'Need help?')
@section('security_info')
    If you have any questions about getting started, feel free to contact our support team at support@mindshelf.com
@endsection
