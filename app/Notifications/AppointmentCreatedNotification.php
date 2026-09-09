<?php

namespace App\Notifications;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentCreatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Cita $cita) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Cita confirmada en MasterCut')
            ->greeting('Hola '.$notifiable->name)
            ->line('Tu cita quedó reservada correctamente.')
            ->line('Fecha: '.$this->cita->fecha)
            ->line('Hora: '.substr((string) $this->cita->hora, 0, 5))
            ->line('Barbero: '.($this->cita->barbero?->nombre_completo ?? 'Por confirmar'))
            ->line('Servicios: '.$this->cita->servicios_nombres_texto)
            ->line('Total: $'.number_format((float) $this->cita->costo, 2))
            ->action('Ver mi cita', route('citas.show', $this->cita))
            ->salutation('Nos vemos en MasterCut.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'cita_id' => $this->cita->id,
        ];
    }
}
