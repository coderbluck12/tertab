<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Your Institution Email - Tertab</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4F46E5;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .button {
            display: inline-block;
            background-color: #4F46E5;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Tertab</h1>
        <p>Institution Email Verification</p>
    </div>
    
    <div class="content">
        <h2>Hello {{ $user->name }},</h2>
        
        <p>You have added an institution to your profile on Tertab. To complete the verification process, please verify your school email address.</p>
        
        <p><strong>Institution Details:</strong></p>
        <ul>
            <li><strong>Institution:</strong> {{ $institution->institution->name ?? 'N/A' }}</li>
            <li><strong>Position:</strong> {{ $institution->position ?? 'N/A' }}</li>
            <li><strong>Email:</strong> {{ $institution->school_email }}</li>
        </ul>
        
        <p>Please click the button below to verify your school email address:</p>
        
        <div style="text-align: center;">
            <a href="{{ url('/institution/verify/' . $token) }}" class="button">
                Verify Email Address
            </a>
        </div>
        
        <p>If the button doesn't work, you can copy and paste this link into your browser:</p>
        <p style="word-break: break-all; color: #4F46E5;">
            {{ url('/institution/verify/' . $token) }}
        </p>
        
        <p><strong>Important:</strong> This verification link will expire after 24 hours. If you need a new verification email, please log in to your account and request a new one.</p>
        
        <p>If you did not add this institution to your profile, please ignore this email.</p>
    </div>
    
    <div class="footer">
        <p>This email was sent from Tertab. Please do not reply to this email.</p>
        <p>If you have any questions, please contact our support team.</p>
    </div>
</body>
</html> 