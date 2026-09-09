<?php

namespace App\Services;

use App\Models\Barbero;
use App\Models\Cita;
use App\Models\Servicio;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

class BarberoAvailabilityService
{
    /**
     * Build available appointment slots for a barber and service selection.
     *
     * @param  Collection<int, Servicio>  $servicios
     * @return Collection<int, array{value: string, label: string, duration: int}>
     */
    public function slotsFor(Barbero $barbero, CarbonImmutable $date, Collection $servicios): Collection
    {
        $duration = max(1, (int) $servicios->sum('duracion'));
        $timezone = config('app.timezone');
        $dayStart = CarbonImmutable::parse($date->format('Y-m-d').' '.config('appointments.opening_time'), $timezone);
        $dayEnd = CarbonImmutable::parse($date->format('Y-m-d').' '.config('appointments.closing_time'), $timezone);
        $interval = max(1, (int) config('appointments.slot_interval'));

        $appointments = $barbero->citas()
            ->whereDate('fecha', $date->toDateString())
            ->where('estado', '!=', 'cancelada')
            ->with('serviciosMany')
            ->get();

        $slots = collect();

        for ($start = $dayStart; $start->addMinutes($duration)->lte($dayEnd); $start = $start->addMinutes($interval)) {
            if ($date->isToday() && $start->lte(now($timezone))) {
                continue;
            }

            $end = $start->addMinutes($duration);
            $overlaps = $appointments->contains(function (Cita $appointment) use ($date, $start, $end, $timezone): bool {
                $appointmentStart = CarbonImmutable::parse(
                    $date->format('Y-m-d').' '.$appointment->hora,
                    $timezone
                );
                $appointmentEnd = $appointmentStart->addMinutes($this->appointmentDuration($appointment));

                return $start->lt($appointmentEnd) && $end->gt($appointmentStart);
            });

            if (! $overlaps) {
                $slots->push([
                    'value' => $start->format('H:i'),
                    'label' => $start->format('H:i'),
                    'duration' => $duration,
                ]);
            }
        }

        return $slots;
    }

    private function appointmentDuration(Cita $appointment): int
    {
        $duration = $appointment->serviciosMany->sum('duracion');

        if ($duration > 0) {
            return (int) $duration;
        }

        if (empty($appointment->servicios)) {
            return 30;
        }

        return max(1, (int) Servicio::whereIn('id', explode(',', $appointment->servicios))
            ->sum('duracion'));
    }
}
