<?php

namespace App\Console\Commands;

use App\Models\Cita;
use App\Notifications\AppointmentReminderNotification;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('appointments:send-reminders')]
#[Description('Envía recordatorios de citas para el día siguiente')]
class SendAppointmentReminders extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $sent = 0;

        Cita::query()
            ->whereDate('fecha', today()->addDay())
            ->where('estado', 'pendiente')
            ->whereNull('recordatorio_enviado_at')
            ->whereNotNull('id_usuario')
            ->with(['usuario', 'barbero', 'serviciosMany'])
            ->each(function (Cita $cita) use (&$sent): void {
                $cita->usuario->notify(new AppointmentReminderNotification($cita));
                $cita->forceFill(['recordatorio_enviado_at' => now()])->saveQuietly();
                $sent++;
            });

        $this->info("{$sent} recordatorio(s) enviado(s).");

        return self::SUCCESS;
    }
}
