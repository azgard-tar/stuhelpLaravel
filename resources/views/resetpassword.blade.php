<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Смена пароля</title>

    <link rel="stylesheet" href="{{ url('/css/app.css') }}">
</head>
<body>
    <form method="POST" action="https://stuhelp.site/api/user/password/{{ $token }}">
        <label for="passwordField">Пароль</label>
        <input type="password" id="passwordField" name="password" class="form-control" aria-describedby="passwordHelpBlock">
        <small id="passwordHelpBlock" class="form-text text-muted">
        Ваш пароль должен быть 6 символов в длину, содержать хотя бы 1 символ: верхнего регистра, нижнего регистра, цифры, спец. символа
        </small>
        <label for="repeatPasswordField1">Повтор пароля</label>
        <input type="password" id="repeatPasswordField1" name="repeatPassword" class="form-control">
        <input type="submit" value="Отправить">
    </form>
    <script src="{{ url('/js/app.js') }}"></script>
</body>
</html>