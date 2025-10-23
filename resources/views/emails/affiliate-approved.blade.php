<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affiliate Application Approved</title>
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
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
        }
        .credentials-box {
            background-color: #fff;
            border: 2px solid #4F46E5;
            border-radius: 5px;
            padding: 20px;
            margin: 20px 0;
        }
        .credential-item {
            margin: 10px 0;
            padding: 10px;
            background-color: #f3f4f6;
            border-radius: 3px;
        }
        .credential-label {
            font-weight: bold;
            color: #4F46E5;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #4F46E5;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .warning {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 Congratulations!</h1>
        <p>Your Affiliate Application Has Been Approved</p>
    </div>
    
    <div class="content">
        <p>Dear {{ $user->name }},</p>
        
        <p>We're excited to inform you that your affiliate application has been approved! Welcome to the {{ config('app.name') }} affiliate program.</p>
        
        <p>Your affiliate account has been created with the following credentials:</p>
        
        <div class="credentials-box">
            <div class="credential-item">
                <span class="credential-label">Email:</span> {{ $user->email }}
            </div>
            <div class="credential-item">
                <span class="credential-label">Temporary Password:</span> {{ $temporaryPassword }}
            </div>
            <div class="credential-item">
                <span class="credential-label">Your Referral Code:</span> {{ $user->referral_code }}
            </div>
        </div>
        
        <div class="warning">
            <strong>⚠️ Important Security Notice:</strong><br>
            Please change your password immediately after your first login for security purposes.
        </div>
        
        <p><strong>Your Referral Link:</strong><br>
        <a href="{{ $user->referral_link }}">{{ $user->referral_link }}</a></p>
        
        <p>You can now:</p>
        <ul>
            <li>Access your affiliate dashboard</li>
            <li>Share your unique referral link</li>
            <li>Track your referrals and earnings</li>
            <li>Earn commissions on successful referrals</li>
        </ul>
        
        <div style="text-align: center;">
            <a href="{{ route('login') }}" class="button">Login to Your Dashboard</a>
        </div>
        
        <p><strong>How to Get Started:</strong></p>
        <ol>
            <li>Login using your email and temporary password</li>
            <li>Change your password in your profile settings</li>
            <li>Copy your referral link from the Referrals page</li>
            <li>Start sharing and earning!</li>
        </ol>
        
        <p>If you have any questions or need assistance, please don't hesitate to contact our support team.</p>
        
        <p>Best regards,<br>
        The {{ config('app.name') }} Team</p>
    </div>
    
    <div class="footer">
        <p>This is an automated email. Please do not reply to this message.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
