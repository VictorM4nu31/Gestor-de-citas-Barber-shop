<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Día y Hora de la Cita</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .appointment-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        .appointment-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .appointment-header h2 {
            margin: 0;
            font-size: 24px;
        }
        .appointment-form .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container appointment-container">
        <div class="appointment-header">
            <h2>Seleccionar Día y Hora de la Cita</h2>
        </div>
        <form class="appointment-form">
            <div class="form-group">
                <label for="appointment-date">Día de la Cita</label>
                <input type="date" id="appointment-date" class="form-control">
            </div>
            <div class="form-group">
                <label for="appointment-time">Hora de la Cita</label>
                <input type="time" id="appointment-time" class="form-control">
            </div>
            <div class="form-group text-center">
                <button type="submit" class="btn btn-primary">Reservar Cita</button>
            </div>
        </form>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>