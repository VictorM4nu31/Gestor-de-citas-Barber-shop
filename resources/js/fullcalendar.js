import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import bootstrapPlugin from '@fullcalendar/bootstrap';

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var today = new Date();
    var year = today.getFullYear();
    var month = String(today.getMonth() + 1).padStart(2, '0'); // Los meses son de 0 a 11, así que sumamos 1
    var day = String(today.getDate()).padStart(2, '0'); // Añadir un 0 a la izquierda si es necesario

    var todayFormatted = `${year}-${month}-${day}`;

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'today prev,next',
            center: 'title',
            right: 'dayGridMonth'
        },
        buttonText: {
            today: 'Hoy',
            month: 'Mes'
        },
        selectable: true,
        select: function(info) {
            if (info.startStr >= todayFormatted) {
                window.location.href = "{{ route('citas.create') }}?date=" + info.startStr;
            }
        },
        validRange: {
            start: todayFormatted,
        },
        dayCellDidMount: function(arg) {
            if (arg.date < todayFormatted) {
                arg.el.classList.add('fc-day-past');
            }
        },
        events: [],
    });

    calendar.render();
});

