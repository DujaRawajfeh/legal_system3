<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعدادات المصادقة الثنائية</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 20px;
            font-size: 14px;
        }
        .container {
            max-width: 550px;
            margin: 0 auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h3 {
            color: #333;
            border-bottom: 2px solid #005f9e;
            padding-bottom: 10px;
            margin-top: 0;
        }
        h4 {
            color: #555;
            margin-top: 20px;
        }
        label {
            display: block;
            margin: 15px 0 5px;
            font-weight: 600;
            color: #333;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: 'Cairo', sans-serif;
            font-size: 14px;
            box-sizing: border-box;
        }
        button, .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-family: 'Cairo', sans-serif;
            font-size: 14px;
            margin: 10px 5px 0 0;
            text-decoration: none;
            display: inline-block;
        }
        button[type="submit"] {
            background: #005f9e;
            color: white;
        }
        button[type="submit"]:hover {
            background: #004580;
        }
        button[style*="background:#b00"] {
            background: #b00 !important;
            color: #fff !important;
        }
        button[style*="background:#b00"]:hover {
            background: #900 !important;
        }
        .btn {
            background: #6c757d;
            color: white;
        }
        .btn:hover {
            background: #5a6268;
        }
        .qr-code {
            text-align: center;
            margin: 20px 0;
        }
        .qr-code svg {
            max-width: 250px;
            height: auto;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        ul li {
            background: #f8f9fa;
            padding: 8px 12px;
            margin: 5px 0;
            border-radius: 4px;
            font-family: monospace;
        }
        .alert {
            padding: 12px;
            border-radius: 4px;
            margin: 15px 0;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>

<div class="container">
    <h3>إعدادات المصادقة الثنائية</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- إذا غير مفعلة --}}
    @if(!auth()->user()->two_factor_enabled)
        <div>
            <h4>امسحي هذا الرمز في تطبيق المصادقة:</h4>

            @if(isset($qrSvg))
                <div class="qr-code">{!! $qrSvg !!}</div>
            @endif

            @if(isset($secret))
                <p style="text-align: center;">أو أدخلي المفتاح يدويًا: <strong style="background: #f0f0f0; padding: 5px 10px; border-radius: 4px; font-family: monospace;">{{ $secret }}</strong></p>
            @endif
        </div>

        <form method="POST" action="{{ route('2fa.enable') }}">
            @csrf
            <label>أدخلي الرمز من التطبيق:</label>
            <input type="text" name="totp_code" required placeholder="123456">
            <button type="submit">تفعيل المصادقة الثنائية</button>
            <a href="javascript:window.close()" class="btn">إلغاء</a>
        </form>
    @else
        {{-- إذا مفعلة --}}
        <div class="alert alert-success">
            ✓ المصادقة الثنائية مفعلة حاليًا
        </div>

        <form method="POST" action="{{ route('2fa.disable') }}">
            @csrf
            <button type="submit" style="background:#b00;color:#fff">تعطيل المصادقة الثنائية</button>
            <a href="javascript:window.close()" class="btn">إغلاق</a>
        </form>

        @if(is_array(auth()->user()->two_factor_recovery_codes))
            <h4>الرموز الاحتياطية:</h4>
            <p style="color: #666; font-size: 13px;">احفظي هذه الرموز في مكان آمن. يمكن استخدام كل رمز مرة واحدة فقط:</p>
            <ul>
                @foreach(auth()->user()->two_factor_recovery_codes as $code)
                    <li>{{ $code }}</li>
                @endforeach
            </ul>
        @endif
    @endif
</div>

</body>
</html>