<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmación de cita</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <h2>Tu cita ha sido agendada</h2>

    <p>
        Hola
        <strong>
            {{ $cita->cliente->nom ?? 'cliente' }}
            {{ $cita->cliente->apellido_p ?? '' }}
            {{ $cita->cliente->apellido_m ?? '' }}
        </strong>,
        tu cita fue registrada correctamente.
    </p>

    <hr>

    <p><strong>Fecha:</strong> {{ $cita->fecha_c }}</p>
    <p><strong>Hora de inicio:</strong> {{ $cita->hora_c }}</p>
    <p><strong>Hora de fin:</strong> {{ $cita->hora_fin }}</p>
    <p><strong>Te atiende:</strong> {{ $cita->personal->nom ?? 'Sin asignar' }}</p>
    <p><strong>Total:</strong> ${{ $cita->total }}</p>

    <h3>Servicios</h3>

    <ul>
        @foreach($cita->detalles as $detalle)
            <li>
                {{ $detalle->tipoServicio->nom ?? 'Servicio' }}
                - ${{ $detalle->subtotal }}
            </li>
        @endforeach
    </ul>

    <hr>

    <p>Gracias por agendar con nosotros.</p>
</body>
</html>