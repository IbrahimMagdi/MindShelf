<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Code - MindShelf</title>
</head>

<body style="margin:0; padding:0; background:linear-gradient(135deg, #f5f1e8 0%, #e8e0d0 100%); font-family:'Georgia', 'Times New Roman', serif;">

<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center" style="padding:40px 20px;">

            <!-- Card Container -->
            <table width="500" cellpadding="0" cellspacing="0" style="background:#fffef9; border-radius:12px; overflow:hidden; box-shadow:0 8px 32px rgba(139, 119, 101, 0.15); border:1px solid #e0d5c7;">

                <!-- Header with Book Theme -->
                <tr>
                    <td style="background:linear-gradient(135deg, #8b6914 0%, #6b4e3d 100%); padding:35px 20px; text-align:center; position:relative;">
                        <!-- Decorative Book Icon -->
                        <div style="font-size:40px; margin-bottom:10px;">📖</div>
                        <h1 style="color:#fff; margin:0; font-size:28px; letter-spacing:2px; text-shadow:0 2px 4px rgba(0,0,0,0.2);">MindShelf</h1>
                        <p style="color:#f0e6d2; margin:8px 0 0; font-style:italic; font-size:14px;">Your Personal Library</p>

                        <!-- Decorative Line -->
                        <div style="width:60px; height:2px; background:rgba(255,255,255,0.4); margin:15px auto 0;"></div>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td style="padding:40px 30px; text-align:center;">

                        <!-- Context -->
                        <div style="background:#faf8f3; border-left:4px solid #8b6914; padding:15px; margin-bottom:25px; text-align:left; border-radius:0 8px 8px 0;">
                            <p style="margin:0; color:#5c4a3d; font-size:15px; line-height:1.6;">
                                🔐 <strong>Protecting Your Library</strong><br>
                                A new device login attempt was detected
                            </p>
                        </div>

                        <h2 style="margin:0 0 10px; color:#4a3f35; font-size:22px; font-weight:normal;">
                            Verification Code
                        </h2>

                        <p style="color:#7d6e5d; font-size:14px; margin:0 0 25px; line-height:1.6;">
                            Enter the code below to access your book collection<br>
                            <span style="font-size:12px; opacity:0.8;">(No one can access without it)</span>
                        </p>

                        <!-- OTP Box - Library Card Style -->
                        <div style="
                            margin:25px auto;
                            padding:20px;
                            background:linear-gradient(to bottom, #fff 0%, #faf8f3 100%);
                            border:2px solid #d4c5b0;
                            border-radius:8px;
                            box-shadow:inset 0 2px 4px rgba(0,0,0,0.05);
                            position:relative;
                        ">
                            <div style="
                                font-size:32px;
                                font-weight:bold;
                                letter-spacing:8px;
                                color:#6b4e3d;
                                font-family:'Courier New', monospace;
                                text-shadow:0 1px 2px rgba(0,0,0,0.05);
                            ">
                                {{ $code }}
                            </div>
                            <div style="margin-top:8px; font-size:11px; color:#a89b8c; letter-spacing:1px;">VERIFICATION CODE</div>
                        </div>

                        <!-- Timer -->
                        <div style="margin:20px 0; padding:10px; background:#fff5f0; border-radius:20px; display:inline-block;">
                            <span style="color:#c45c3e; font-size:13px;">⏳ Expires in 10 minutes</span>
                        </div>

                        <!-- Security Note -->
                        <p style="color:#9c8b7a; font-size:12px; margin:25px 0 0; font-style:italic; border-top:1px dashed #e0d5c7; padding-top:15px;">
                            If this wasn't you, please change your password immediately to protect your library
                        </p>

                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="background:#f5f1e8; padding:20px; text-align:center; border-top:1px solid #e8e0d0;">
                        <p style="font-size:12px; color:#8b7355; margin:0 0 5px;">
                            📚 MindShelf - Your Digital Library
                        </p>
                        <p style="font-size:11px; color:#a89b8c; margin:0;">
                            This is an automated email, please do not reply
                        </p>
                    </td>
                </tr>

            </table>

            <!-- Safety Note Outside -->
            <p style="margin-top:20px; font-size:11px; color:#8b7355; text-align:center;">
                🔒 We care about your privacy and data security
            </p>

        </td>
    </tr>
</table>

</body>
</html>
