<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f4f4f4; padding: 30px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
        <tr>
            <td style="padding: 30px;">
                <h1 style="font-size: 24px; color: #333; margin-bottom: 20px;">{{ $title }}</h1>
                <div style="font-size: 16px; color: #444; margin-bottom: 30px;">
                    {!! $body !!}
                </div>
                @if(isset($button_text) && isset($button_url))
                    <p style="margin-bottom: 30px;">
                        <a href="{{ $button_url }}" style="display:inline-block;padding:12px 24px;background:#3490dc;color:#fff;text-decoration:none;border-radius:4px;font-weight:bold;">
                            {{ $button_text }}
                        </a>
                    </p>
                @endif
                <p style="color: #888; font-size: 14px;">Thanks,<br><strong>{{ config('app.name') }} Team</strong></p>
            </td>
        </tr>
    </table>
</body>
</html>
