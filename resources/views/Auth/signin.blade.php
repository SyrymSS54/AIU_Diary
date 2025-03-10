<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type">
        <meta content="text/html">
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Вход</title>
        @Vite(['resources/js/auth.js'])
        <link rel="stylesheet" href="https://cdn.webix.com/edge/webix.css">
    </head>
    <body>
        <div id="auth-container" style="text-align: center;
    line-height: 100vh;"></div>
    </body>
</html>