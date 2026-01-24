<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        table{
            border:1px solid #ccc;
        }
    </style>
</head>
<body>

<h2> مرحبا  </h2>
<p> لديك طلب جديد علي المتجر الالكتروني   </p>
<table class="table table-bordered">

    <thead>
    <tr>
        <td>
            الاسم
        </td>
        <td> رقم الهاتف   </td>
        <td> العنوان  </td>
        <td> المجموع الكلي   </td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>
            {{ $name }}
        </td>
        <td>
            {{ $phone }}
        </td>
        <td>
            {{ $address }}
        </td>
        <td>
            {{ $grand_total }}
        </td>
    </tr>
    </tbody>
</table>

</body>
</html>
