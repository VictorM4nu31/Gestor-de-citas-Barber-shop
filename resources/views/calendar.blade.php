<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://unpkg.com/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
    <link href="https://unpkg.com/fullcalendar@5.11.3/locales/es-all.min.css" rel="stylesheet" />
    <style>
        /* Estilo personalizado para los días pasados (se ocultan en lugar de cambiar el color) */
        .fc-day-past {
            display: none !important;
        }

        /* Estilo para el título en la parte superior */
        .header-title {
            margin-bottom: 2rem;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 700;
            color: #2D3748; /* Color gris oscuro */
        }
    </style>
    <script src="https://unpkg.com/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://unpkg.com/fullcalendar@5.11.3/locales/es-all.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-8">
        <!-- Título en la parte superior -->
        <div class="header-title">Genera tu cita</div>
        <!-- Elemento para mostrar la fecha de hoy -->
        <div id="today-date" class="mb-4 text-lg font-semibold text-gray-800"></div>
        <div id="calendar"></div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            // Obtener la fecha de hoy en formato YYYY-MM-DD con zona horaria local
            var today = new Date();
            var year = today.getFullYear();
            var month = String(today.getMonth() + 1).padStart(2, '0'); // Los meses son de 0 a 11, así que sumamos 1
            var day = String(today.getDate()).padStart(2, '0'); // Añadir un 0 a la izquierda si es necesario

            var todayFormatted = `${year}-${month}-${day}`;

            // Mostrar la fecha de hoy en el div con id 'today-date'
            document.getElementById('today-date').textContent = `Hoy es: ${todayFormatted}`;

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es', // Establecer el idioma del calendario a español
                headerToolbar: {
                    left: 'today prev,next', // Botón de hoy, anterior, siguiente
                    center: 'title',         // Título del calendario
                    right: 'dayGridMonth'   // Solo vista de mes
                },
                buttonText: {
                    today: 'Hoy', // Cambiar el texto del botón de "Hoy"
                    month: 'Mes'
                },
                selectable: true,
                select: function(info) {
                    if (info.startStr >= todayFormatted) {
                        window.location.href = "{{ route('citas.create') }}?date=" + info.startStr;
                    }
                },
                validRange: {
                    start: todayFormatted, // Establecer el rango válido desde hoy
                },
                dayCellDidMount: function(arg) {
                    // Ocultar los días pasados
                    if (arg.date < todayFormatted) {
                        arg.el.classList.add('fc-day-past');
                    }
                },
                events: [], // Aquí puedes agregar eventos dinámicos si es necesario
            });

            calendar.render();
        });
    </script>
</body>
</html>
