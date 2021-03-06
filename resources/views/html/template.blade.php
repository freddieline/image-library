<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ mix( '/css/app.css' ) }}">
    <title>Check your carbon</title>
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Pompiere&display=swap" rel="stylesheet">
</head>
<body>

    <div id="app">
        <app :composer="{{ json_encode( get_defined_vars()[ '__data' ] ) }}"></app>
    </div>

</body>
<script src="{{ mix( '/js/app.js' ) }}"></script>

</html>
