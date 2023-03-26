<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="/dist/js/index.js"></script>
</head>
<body>

<div class="wrapper">
    <header class="header">
        <div class="container">
            <div class="header__row">
                <div class="header__logo">
                    {{ env('APP_NAME') }}
                </div>
                <x-menu
                    :tagClasses="['header__nav']"
                ></x-menu>
            </div>
        </div>
    </header>

    {{ $slot }}
</div>
</body>
</html>
