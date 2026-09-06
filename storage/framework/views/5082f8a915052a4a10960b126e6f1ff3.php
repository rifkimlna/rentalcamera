<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kode Verifikasi Telepon</title>
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
            background-color: #1e3a5f;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 5px 5px;
            border: 1px solid #dee2e6;
        }
        .otp-code {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
            border: 2px dashed #1e3a5f;
        }
        .otp-digits {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 8px;
            color: #1e3a5f;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6c757d;
            font-size: 12px;
        }
        .note {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 5px;
            padding: 10px 15px;
            margin-top: 20px;
            font-size: 13px;
            color: #664d03;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin:0;">Stekpro Multimedia & Broadcast</h1>
        <p style="margin:5px 0 0;opacity:0.9;">Verifikasi Nomor Telepon</p>
    </div>

    <div class="content">
        <p>Halo, <strong><?php echo e($user->nama); ?></strong>,</p>
        <p>Kamu menerima email ini karena melakukan verifikasi nomor telepon pada akunmu.</p>

        <div class="otp-box">
            <p style="margin-bottom:5px;">Gunakan kode berikut untuk memverifikasi nomor teleponmu:</p>
            <div class="otp-code"><?php echo e($otp); ?></div>
        </div>

        <div class="note">
            <strong>Catatan:</strong> Kode ini hanya berlaku selama <strong>5 menit</strong>. Jika kode tidak kamu minta, abaikan email ini.
        </div>
    </div>

    <div class="footer">
        <p>Jika kamu tidak meminta kode ini, mohon abaikan email ini.</p>
        <p>&copy; <?php echo e(date('Y')); ?> Stekpro Multimedia & Broadcast</p>
    </div>
</body>
</html><?php /**PATH C:\laragon\www\PROJECT KP\resources\views/emails/phone_otp.blade.php ENDPATH**/ ?>