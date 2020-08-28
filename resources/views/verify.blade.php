<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>

<div>
    Привет, {{ $name }},
    <br>
    Спасибо за регистрацию на сайте stuhelp.site! Не забудь закончить регистрацию в своей профиле.
    <br>
    Щелкните ссылку ниже или скопируйте ее в адресную строку браузера, чтобы подтвердить свой адрес электронной почты:
    <br>

    <a href="https://stuhelp.site/api/user/verify/{{ $verification_code }}">Подтвердить электронный адрес </a>

    <br/>
</div>

</body>
</html>