<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Identity Verification Rejected</title>
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
            background-color: #dc2626;
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
            background-color: #2563eb;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
        .rejection-reason {
            background-color: #fef2f2;
            border-left: 4px solid #dc2626;
            padding: 15px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Identity Verification Rejected</h1>
    </div>
    
    <div class="content">
        <p>Dear {{ $verificationRequest->user->name }},</p>
        
        <p>We regret to inform you that your identity verification request has been rejected by our review team.</p>
        
        @if($verificationRequest->rejection_reason)
            <div class="rejection-reason">
                <h3>Reason for Rejection:</h3>
                <p>{{ $verificationRequest->rejection_reason }}</p>
            </div>
        @endif
        
        <p><strong>What happens next?</strong></p>
        <ul>
            <li>You can resubmit your verification documents with the necessary corrections</li>
            <li>Please ensure your documents are clear, legible, and meet our requirements</li>
            <li>Make sure all information is visible and matches your account details</li>
        </ul>
        
        <p><strong>Document Requirements:</strong></p>
        <ul>
            <li>High-quality image or PDF format</li>
            <li>All text must be clearly readable</li>
            <li>Document must be valid and not expired</li>
            <li>Full document must be visible (no cropped edges)</li>
        </ul>
        
        <div style="text-align: center;">
            <a href="{{ route('verification.required') }}" class="button">Resubmit Verification</a>
        </div>
        
        <p>If you have any questions about this decision or need assistance with the verification process, please don't hesitate to contact our support team.</p>
        
        <p>Thank you for your understanding.</p>
        
        <p>Best regards,<br>
        The {{ config('app.name') }} Team</p>
    </div>
    
    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
