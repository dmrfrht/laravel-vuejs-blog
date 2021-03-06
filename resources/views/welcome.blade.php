<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/css/all.css">

    <script>
        (function() {
            window.laravel = {
                csrfToken: '{{ csrf_token() }}'
            }
        })()

    </script>

    <title>Laravel + Vue Fullstack Blog</title>
</head>

<body>

    <div id="app">
        <main-app></main-app>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
