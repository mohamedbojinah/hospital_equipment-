<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - نظام إدارة الآلات</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--bg-color) 100%);
        }
        .auth-card {
            width: 100%;
            max-width: 400px;
            padding: 2.5rem;
            text-align: center;
        }
        .auth-logo {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        .auth-title {
            margin-bottom: 2rem;
            color: var(--text-main);
        }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>
