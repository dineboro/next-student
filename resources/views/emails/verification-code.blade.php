<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background: #3b82f6;
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            padding: 40px 30px;
        }
        .content h2 {
            color: #333;
            margin-top: 0;
        }
        .code-container {
            text-align: center;
            margin: 30px 0;
        }
        .code {
            font-size: 36px;
            font-weight: bold;
            color: #3b82f6;
            letter-spacing: 8px;
            padding: 20px;
            background: #f0f9ff;
            border: 2px dashed #3b82f6;
            border-radius: 8px;
            display: inline-block;
        }
        .info-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            padding: 20px;
            background: #f9fafb;
            color: #666;
            font-size: 14px;
        }
        .footer a {
            color: #3b82f6;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🔧 NextStudent</h1>
    </div>

    <div class="content">
        <h2>Hello {{ $user->first_name }}!</h2>

        <p>Thank you for registering with NextStudent. To complete your registration, please verify your email address using the verification code below:</p>

        <div class="code-container">
            <div class="code">{{ $code }}</div>
        </div>

        <div class="info-box">
            <strong>⏰ Important:</strong> This code will expire in 24 hours.
        </div>

        <p>If you didn't create an account with NextStudent, please ignore this email.</p>

        <p>After verifying your email, your account will be reviewed by an administrator before you can log in.</p>

        <p>Best regards,<br>The NextStudent Team</p>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} NextStudent. All rights reserved.</p>
        <p>
            <a href="https://www.nextstudent.org">Visit our website</a> |
            <a href="mailto:noreply@nextstudent.org">Contact support</a>
        </p>
    </div>
</div>
</body>
</html>
