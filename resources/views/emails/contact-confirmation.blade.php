<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ __('I got your message — blckt.') }}</title>
<style>
    body { margin: 0; padding: 0; background: #f5f5f5; font-family: Arial, Helvetica, sans-serif; color: #111111; }
    .wrap { max-width: 560px; margin: 0 auto; padding: 32px 24px; }
    .card { background: #ffffff; border: 1px solid #e5e5e5; border-radius: 10px; padding: 32px; }
    .brand { font-size: 20px; font-weight: 700; letter-spacing: -0.02em; margin: 0 0 24px; }
    .text { font-size: 15px; line-height: 1.6; margin: 0 0 16px; }
    .sign { font-size: 15px; line-height: 1.6; margin-top: 24px; color: #555555; }
</style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <p class="brand">blckt.</p>
            <p class="text">{{ __('Hi :name,', ['name' => $name]) }}</p>
            <p class="text">{{ __('Thanks for reaching out — I’ve received your message and I’ll get back to you within 24 hours.') }}</p>
            <p class="text">{{ __('If this wasn’t you, you can safely ignore this email.') }}</p>
            <p class="sign">— Levente<br>blckt.</p>
        </div>
    </div>
</body>
</html>
