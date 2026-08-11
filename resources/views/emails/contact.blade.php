<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New inquiry — blckt.</title>
<style>
    body { margin: 0; padding: 0; background: #f5f5f5; font-family: Arial, Helvetica, sans-serif; color: #111111; }
    .wrap { max-width: 560px; margin: 0 auto; padding: 32px 24px; }
    .card { background: #ffffff; border: 1px solid #e5e5e5; border-radius: 10px; padding: 32px; }
    .brand { font-size: 20px; font-weight: 700; letter-spacing: -0.02em; margin: 0 0 24px; }
    .row { margin-bottom: 18px; }
    .label { font-size: 12px; text-transform: uppercase; letter-spacing: 0.08em; color: #888888; margin-bottom: 4px; }
    .value { font-size: 15px; line-height: 1.5; }
    .value a { color: #111111; }
    .message { white-space: pre-wrap; }
    .meta { margin-top: 24px; padding-top: 16px; border-top: 1px solid #eeeeee; font-size: 12px; color: #999999; line-height: 1.6; }
</style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <p class="brand">blckt.</p>

            <div class="row">
                <div class="label">From</div>
                <div class="value">{{ $data['name'] }} &lt;<a href="mailto:{{ $data['email'] }}">{{ $data['email'] }}</a>&gt;</div>
            </div>

            @if (!empty($data['budget']))
                <div class="row">
                    <div class="label">Budget</div>
                    <div class="value">{{ number_format((float) $data['budget'], 0, ',', ' ') }} Ft</div>
                </div>
            @endif

            <div class="row">
                <div class="label">Message</div>
                <div class="value message">{{ $data['message'] }}</div>
            </div>

            <div class="meta">
                Sent {{ $data['submitted_at'] }} &middot; site language: {{ strtoupper($data['locale']) }}<br>
                From page: {{ $data['source_page'] }}
            </div>
        </div>
    </div>
</body>
</html>
