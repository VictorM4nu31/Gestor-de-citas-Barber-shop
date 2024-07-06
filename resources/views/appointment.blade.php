<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FullCalendar Example</title>
    @vite(['resources/js/app.js', 'resources/sass/app.scss'])
    <link href="{{ asset('node_modules/@fullcalendar/core/main.css') }}" rel="stylesheet">
    <link href="{{ asset('node_modules/@fullcalendar/daygrid/main.css') }}" rel="stylesheet">
    <link href="{{ asset('node_modules/@fullcalendar/timegrid/main.css') }}" rel="stylesheet">
    <link href="{{ asset('node_modules/@fullcalendar/bootstrap/main.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div id="calendar"></div>
    </div>
</body>
</html>