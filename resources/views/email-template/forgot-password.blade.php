<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Password</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f4f6f8; padding:20px;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table width="600" style="background:#ffffff; padding:20px; border-radius:8px;">
                    <tr>
                        <td align="center">
                            <h2 style="color:#333;">Reset Your Password</h2>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p>Hello {{ $email }},</p>
                            <p>You requested to reset your password. Click the button below:</p>

                            <p style="text-align:center;">
                                <a href="{{ $resetUrl }}"
                                   style="background:#0d6efd;color:#fff;padding:12px 20px;
                                   text-decoration:none;border-radius:5px;display:inline-block;">
                                    Reset Password
                                </a>
                            </p>

                            <p>This link will expire in {{ $expire }} minutes.</p>

                            <p>If you did not request this, please ignore this email.</p>

                            <br>
                            <p>Thanks,<br><strong>{{ config('app.name') }}</strong></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
