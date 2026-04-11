<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MindShelf')</title>
</head>

<body style="margin:0; padding:40px 20px; background:#f5f5f5; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center">

            <!-- Main Card -->
            <table width="400" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:24px; box-shadow:0 4px 24px rgba(0,0,0,0.08);">

                <!-- Header: Logo -->
                <tr>
                    <td align="center" style="padding:48px 40px 32px;">
                        <div style="width:56px; height:56px; background:#1a1a1a; border-radius:16px; display:inline-flex; align-items:center; justify-content:center; font-size:28px; color:#fff; box-shadow:0 4px 12px rgba(0,0,0,0.15);">
                            📖
                        </div>
                    </td>
                </tr>

                <!-- Dynamic Title -->
                <tr>
                    <td align="center" style="padding:0 40px 32px;">
                        <h1 style="margin:0; font-size:22px; font-weight:600; color:#1a1a1a; line-height:1.3;">
                            @yield('heading')
                        </h1>
                        @hasSection('subheading')
                            <p style="margin:12px 0 0; font-size:14px; color:#666; line-height:1.5;">
                                @yield('subheading')
                            </p>
                        @endif
                    </td>
                </tr>

                <!-- Dynamic Content -->
                <tr>
                    <td style="padding:0 40px 32px;">
                        @yield('content')
                    </td>
                </tr>

                <!-- Security Notice (Optional) -->
                @hasSection('security_info')
                    <tr>
                        <td style="padding:0 40px 32px;">
                            <div style="background:#faf8f3; border-radius:16px; padding:20px; border-left:4px solid #8b6914;">
                                <p style="margin:0 0 12px; font-size:14px; font-weight:600; color:#5c4a3d;">
                                    ⚠️ @yield('security_title', 'Was this request not made by you?')
                                </p>
                                <p style="margin:0; font-size:13px; color:#7d6e5d; line-height:1.6;">
                                    @yield('security_info')
                                </p>
                            </div>
                        </td>
                    </tr>
                @endif

                <!-- Footer -->
                <tr>
                    <td align="center" style="padding:0 40px 48px;">
                        <p style="margin:0 0 16px; font-size:12px; color:#999;">
                            This is an automated message. <strong style="color:#666;">Please do not reply.</strong>
                        </p>

                        @hasSection('footer_badge')
                            <div style="display:inline-block; background:#333; color:#fff; padding:8px 16px; border-radius:20px; font-size:12px; font-weight:500;">
                                @yield('footer_badge')
                            </div>
                        @endif
                    </td>
                </tr>

            </table>

            <!-- Copyright -->
            <p style="margin-top:24px; font-size:12px; color:#999;">
                © 2026 MindShelf. All rights reserved.
            </p>

        </td>
    </tr>
</table>

</body>
</html>
