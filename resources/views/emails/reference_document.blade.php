<!DOCTYPE html>
<html>
<head>
    <title>Reference Document Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 20px;
            font-weight: bold;
            border-radius: 8px 8px 0 0;
        }
        .content {
            padding: 20px;
            color: #333;
            line-height: 1.6;
        }
        .footer {
            text-align: center;
            padding: 10px;
            font-size: 14px;
            color: #777;
        }
        .button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">Tertab Reference Document Available</div>

    <div class="content">
        <p>Dear <strong>{{ $reference->student->name }}</strong>,</p>

        <p>Your lecturer, <strong>{{ $reference->lecturer->name }}</strong>, has uploaded a reference document for you.</p>

        <p>You can download the document using the button below:</p>

        <p style="text-align: center;">
            <a href="{{ asset('storage/' . $reference->document_path) }}" class="button">Download Document</a>
        </p>

        <p>If you have any questions, please reach out to your lecturer.</p>

        <p>Best regards,</p>
        <p><strong>Tertab</strong></p>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} Tertab. All rights reserved.
    </div>
</div>

</body>
</html>


{{--<!DOCTYPE html>--}}
{{--<html>--}}
{{--<head>--}}
{{--    <title>Tertab Reference Document</title>--}}
{{--</head>--}}
{{--<body>--}}
{{--<p>Dear {{ $reference->student->name }},</p>--}}

{{--<p>Your lecturer, {{ $reference->lecturer->name }}, has uploaded a reference document for you.</p>--}}

{{--<p>Please find the attached document.</p>--}}

{{--<p>Best regards,</p>--}}
{{--<p>Your University</p>--}}
{{--</body>--}}
{{--</html>--}}
