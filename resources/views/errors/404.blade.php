
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - الصفحة غير موجودة</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to bottom, #ff5722, #ff7043);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .container {
            max-width: 600px;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.6);
            border-radius: 15px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        }

        h1 {
            font-size: 80px;
            margin: 0;
            color: #ffd700;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
        }

        p {
            font-size: 20px;
            margin: 20px 0;
            line-height: 1.8;
        }

        a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff9800;
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease-in-out;
        }

        a:hover {
            background-color: #ffcc80;
            color: #000;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>404</h1>
        <p>عذرًا، الصفحة التي تحاول الوصول إليها غير موجودة أو قد تمت إزالتها.</p>
        <a href="{{ url('/') }}">العودة إلى الصفحة الرئيسية</a>
    </div>
</body>

</html>
