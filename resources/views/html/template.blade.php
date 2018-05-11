<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ mix( '/css/app.css' ) }}">
    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
</head>
<body>

    <div id="app">
        <app :composer="{{ json_encode( get_defined_vars()[ '__data' ] ) }}"></app>
    </div>

</body>
<script src="{{ mix( '/js/app.js' ) }}"></script>

</html>
