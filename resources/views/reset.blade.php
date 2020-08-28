<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>

<div>
    Привет, {{ $name }},
    <br><br>
    Это сообщение было отправлено с сайта stuhelp.site, так как вы решили сменить ваш пароль. Если нет - можете начинать беспокоится.
    <br>
    Щелкните ссылку ниже или скопируйте ее в адресную строку браузера, чтобы сменить пароль:
    <br>
    <a href="https://stuhelp.site/api/user/password/{{ $verification_code }}">Сменить пароль</a><br>
    Строка для вставки: https://stuhelp.site/api/user/password/{{ $verification_code }}

    <br/>
</div>

</body>
</html>