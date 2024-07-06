<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FullCalendar Example</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <link href="{{ asset('node_modules/@fullcalendar/core/main.css') }}" rel="stylesheet">
    <link href="{{ asset('node_modules/@fullcalendar/daygrid/main.css') }}" rel="stylesheet">
    <link href="{{ asset('node_modules/@fullcalendar/bootstrap/main.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <div id="calendar" class="bg-white p-5 rounded shadow-lg"></div>
    </div>
</body>
</html>
