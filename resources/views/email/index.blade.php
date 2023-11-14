<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Email</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .logo {
            text-align: center;
        }

        .logo img {
            max-width: 100px;
            height: auto;
        }

        .header {
            text-align: center;
            color: #333;
        }

        .otp-box {
            background-color: #f1f1f1;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            text-align: center;
        }

        .otp-code {
            font-size: 36px;
            color: #333;
            letter-spacing: 10px;
        }

        .info-text {
            margin-top: 20px;
            font-size: 14px;
            color: #777;
            text-align: center;
        }

        .thank-you {
            margin-top: 20px;
            font-size: 16px;
            color: #333;
            text-align: center;
        }

        .expiry-notice {
            margin-top: 10px;
            font-size: 12px;
            color: #ff0000; /* Warna merah untuk menarik perhatian */
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Company Logo">
        </div>
        <div class="header">
            <h2>Your OTP Code</h2>
        </div>

        <div class="otp-box">
            <p class="otp-code"><strong>{{ $otp }}</strong></p>
        </div>

        <p class="info-text">This OTP is valid for a short period. Do not share it with anyone.</p>

        <p class="expiry-notice">Note: This OTP will expire in 5 minutes.</p>

        <p class="thank-you">Thank you!</p>
    </div>
</body>
</html>
