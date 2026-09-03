<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>طباعة QR Code - {{ $equipment->name }}</title>
    <style>
        body { font-family: sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; background: #fff; }
        .print-container { text-align: center; border: 2px dashed #ccc; padding: 2rem; border-radius: 10px; }
        .title { font-size: 1.5rem; font-weight: bold; margin-bottom: 0.5rem; }
        .subtitle { color: #666; margin-bottom: 1.5rem; }
        @media print {
            body { height: auto; display: block; }
            .print-container { border: none; padding: 0; }
            button { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="print-container">
        <div class="title">{{ $equipment->name }}</div>
        <div class="subtitle">SN: {{ $equipment->serial_number }}</div>
        
        <div>{!! $qrCode !!}</div>
        
        <div style="margin-top: 1rem; font-size: 0.8rem; color: #999;">مستشفى مديكال-تك</div>
        
        <button onclick="window.print()" style="margin-top: 2rem; padding: 0.5rem 1rem; cursor: pointer;">طباعة</button>
    </div>
</body>
</html>
