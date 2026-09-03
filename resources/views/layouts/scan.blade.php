<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الآلة (QR Scan)</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        body {
            background-color: var(--bg-color);
            padding: 1rem;
        }
        .scan-container {
            max-width: 600px;
            margin: 0 auto;
        }
        .equipment-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .equipment-icon {
            font-size: 4rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="scan-container">
        @yield('content')
    </div>
</body>
</html>
