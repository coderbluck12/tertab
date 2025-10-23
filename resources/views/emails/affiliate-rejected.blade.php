<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affiliate Application Update</title>
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
            background-color: #6b7280;
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
        .info-box {
            background-color: #fff;
            border-left: 4px solid #6b7280;
            padding: 15px;
            margin: 20px 0;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Affiliate Application Update</h1>
    </div>
    
    <div class="content">
        <p>Dear {{ $application->name }},</p>
        
        <p>Thank you for your interest in becoming an affiliate partner with {{ config('app.name') }}.</p>
        
        <p>After careful review, we regret to inform you that we are unable to approve your affiliate application at this time.</p>
        
        @if($adminNotes)
        <div class="info-box">
            <strong>Feedback from our team:</strong><br>
            {{ $adminNotes }}
        </div>
        @endif
        
        <p>We encourage you to:</p>
        <ul>
            <li>Review our affiliate program requirements</li>
            <li>Consider reapplying in the future</li>
            <li>Explore other ways to engage with our platform</li>
        </ul>
        
        <p>You're still welcome to register as a regular user and enjoy all the benefits of our platform.</p>
        
        <div style="text-align: center;">
            <a href="{{ route('register') }}" class="button">Register as User</a>
        </div>
        
        <p>If you have any questions about this decision or would like more information, please feel free to contact our support team.</p>
        
        <p>Thank you for your understanding.</p>
        
        <p>Best regards,<br>
        The {{ config('app.name') }} Team</p>
    </div>
    
    <div class="footer">
        <p>This is an automated email. Please do not reply to this message.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
