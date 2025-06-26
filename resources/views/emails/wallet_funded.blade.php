<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Wallet Funded Successfully!</title>
</head>
<body>
    <h1>Wallet Funded Successfully!</h1>
    <p>Hello {{ $userName }},</p>
    <p>This is to confirm that your wallet has been successfully funded.</p>
    <p><strong>Amount Credited:</strong> ₦{{ number_format($amount, 2) }}</p>
    <p><strong>New Balance:</strong> ₦{{ number_format($balance, 2) }}</p>
    <p>Thank you for using our service.</p>
    <p>Thanks,<br>{{ config('app.name') }}</p>
</body>
</html> 