<?php

namespace App\Mail;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class CitaConfirmadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public Cita $cita;
    public string $googleCalendarUrl;

    public function __construct(Cita $cita)
    {
        $this->cita = $cita;

        $inicio = Carbon::parse($cita->fecha_c . ' ' . $cita->hora_c)->format('Ymd\THis');
        $fin = Carbon::parse($cita->fecha_c . ' ' . $cita->hora_fin)->format('Ymd\THis');

        $serviciosTexto = $cita->detalles
            ->map(function ($detalle) {
                return $detalle->tipoServicio->nombre
                    ?? $detalle->tipoServicio->nom
                    ?? 'Servicio';
            })
            ->implode(', ');

        $titulo = 'Cita en Estética Nova';
        $descripcion = 'Tu cita ha sido agendada correctamente. Servicios: ' . $serviciosTexto;
        $ubicacion = 'Estética Nova';

        $this->googleCalendarUrl = 'https://calendar.google.com/calendar/render?action=TEMPLATE'
            . '&text=' . urlencode($titulo)
            . '&dates=' . urlencode($inicio . '/' . $fin)
            . '&details=' . urlencode($descripcion)
            . '&location=' . urlencode($ubicacion);
    }

    public function build()
    {
        return $this->subject('Confirmación de tu cita')
            ->view('emails.cita-confirmada');
    }
}