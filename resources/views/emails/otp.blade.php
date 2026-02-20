<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi Login</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f0f4f8; color: #1e293b; }
        .wrapper { max-width: 560px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%); padding: 32px 40px; text-align: center; }
        .header h1 { color: #ffffff; font-size: 20px; font-weight: 700; letter-spacing: 0.5px; }
        .header p { color: rgba(255,255,255,0.75); font-size: 13px; margin-top: 4px; }
        .body { padding: 40px; }
        .greeting { font-size: 16px; color: #334155; margin-bottom: 16px; }
        .info { font-size: 14px; color: #64748b; line-height: 1.7; margin-bottom: 28px; }
        .otp-box { background: #f1f5f9; border: 2px dashed #3b82f6; border-radius: 10px; text-align: center; padding: 24px; margin-bottom: 28px; }
        .otp-box .label { font-size: 12px; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; }
        .otp-code { font-size: 42px; font-weight: 800; letter-spacing: 10px; color: #1e3a5f; font-family: 'Courier New', monospace; }
        .timer { text-align: center; font-size: 13px; color: #ef4444; font-weight: 600; margin-bottom: 28px; }
        .warning { background: #fff7ed; border-left: 4px solid #f97316; border-radius: 0 8px 8px 0; padding: 14px 16px; font-size: 13px; color: #92400e; line-height: 1.6; margin-bottom: 24px; }
        .footer { background: #f8fafc; padding: 20px 40px; text-align: center; font-size: 12px; color: #94a3b8; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>🔐 Verifikasi Login Admin</h1>
            <p>DITRES PPA PPO POLDA NTB</p>
        </div>
        <div class="body">
            <p class="greeting">Halo, <strong>{{ $name }}</strong>!</p>
            <p class="info">
                Anda baru saja mencoba masuk ke <strong>Panel Admin PPA Polda NTB</strong>.
                Gunakan kode OTP berikut untuk menyelesaikan proses login Anda.
            </p>

            <div class="otp-box">
                <div class="label">Kode Verifikasi OTP</div>
                <div class="otp-code">{{ $otp }}</div>
            </div>

            <p class="timer">⏱ Kode ini berlaku selama <strong>5 menit</strong> dan hanya bisa digunakan sekali.</p>

            <div class="warning">
                <strong>⚠ Peringatan Keamanan:</strong><br>
                Jangan bagikan kode ini kepada siapapun. Tim kami tidak pernah meminta kode OTP Anda.
                Jika Anda tidak merasa melakukan login, segera abaikan email ini.
            </div>
        </div>
        <div class="footer">
            Email ini dikirim otomatis oleh sistem. Jangan balas email ini.<br>
            &copy; {{ date('Y') }} DITRES PPA PPO POLDA NTB — Semua hak dilindungi.
        </div>
    </div>
</body>
</html>
