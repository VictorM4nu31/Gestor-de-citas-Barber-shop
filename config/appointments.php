<?php

return [
    'opening_time' => env('APPOINTMENTS_OPENING_TIME', '09:00'),
    'closing_time' => env('APPOINTMENTS_CLOSING_TIME', '20:00'),
    'slot_interval' => (int) env('APPOINTMENTS_SLOT_INTERVAL', 30),
];
