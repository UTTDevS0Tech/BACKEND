<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmación de cita</title>
</head>
<body style="margin:0; padding:0; background-color:#d9d3b2; font-family: Arial, Helvetica, sans-serif; color:#6b4b34;">

    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#d9d3b2; margin:0; padding:24px 0;">
        <tr>
            <td align="center">

                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;">
                    <tr>
                        <td style="padding:0 20px 10px 20px;">
                            <p style="margin:0; font-size:13px; color:#7b725c;">
                                Estética Nova
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0 20px 14px 20px;">
                            <span style="display:inline-block; background:#e8e1c8; color:#fff; border-radius:999px; padding:8px 16px; font-size:13px;">
                                Belleza & Bienestar
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0 20px 10px 20px;">
                            <h1 style="margin:0; font-size:52px; line-height:0.95; color:#fff; font-weight:800;">
                                Tu cita ha sido agendada
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0 20px 24px 20px;">
                            <p style="margin:0; font-size:16px; line-height:1.7; color:#fffdf7;">
                                Hola
                                <strong>
                                    {{ $cita->cliente->nom ?? 'Cliente' }}
                                    {{ $cita->cliente->apellido_p ?? '' }}
                                    {{ $cita->cliente->apellido_m ?? '' }}
                                </strong>,
                                tu reservación quedó confirmada correctamente.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0 20px 24px 20px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f7f2ea; border-radius:18px;">
                                <tr>
                                    <td style="padding:28px 26px;">

                                        <h2 style="margin:0 0 22px 0; font-size:20px; color:#6b4b34;">
                                            Detalles de tu cita
                                        </h2>

                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td style="padding:12px 0; border-bottom:1px solid #dfd1c1; font-size:14px; color:#8c7761;">Número de cita</td>
                                                <td align="right" style="padding:12px 0; border-bottom:1px solid #dfd1c1; font-size:14px; color:#6b4b34;">#{{ $cita->id }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:12px 0; border-bottom:1px solid #dfd1c1; font-size:14px; color:#8c7761;">Fecha</td>
                                                <td align="right" style="padding:12px 0; border-bottom:1px solid #dfd1c1; font-size:14px; color:#6b4b34;">
                                                    {{ \Carbon\Carbon::parse($cita->fecha_c)->format('d/m/Y') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:12px 0; border-bottom:1px solid #dfd1c1; font-size:14px; color:#8c7761;">Hora de inicio</td>
                                                <td align="right" style="padding:12px 0; border-bottom:1px solid #dfd1c1; font-size:14px; color:#6b4b34;">
                                                    {{ \Carbon\Carbon::parse($cita->hora_c)->format('g:i A') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:12px 0; border-bottom:1px solid #dfd1c1; font-size:14px; color:#8c7761;">Hora de fin</td>
                                                <td align="right" style="padding:12px 0; border-bottom:1px solid #dfd1c1; font-size:14px; color:#6b4b34;">
                                                    {{ \Carbon\Carbon::parse($cita->hora_fin)->format('g:i A') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:12px 0; border-bottom:1px solid #dfd1c1; font-size:14px; color:#8c7761;">Te atiende</td>
                                                <td align="right" style="padding:12px 0; border-bottom:1px solid #dfd1c1; font-size:14px; color:#6b4b34;">
                                                    {{ $cita->personal->nom ?? '' }}
                                                    {{ $cita->personal->apellido_p ?? '' }}
                                                    {{ $cita->personal->apellido_m ?? '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:12px 0; font-size:14px; color:#8c7761;">Total</td>
                                                <td align="right" style="padding:12px 0; font-size:16px; font-weight:bold; color:#c47d2b;">
                                                    ${{ number_format($cita->total, 2) }}
                                                </td>
                                            </tr>
                                        </table>

                                        <h3 style="margin:28px 0 14px 0; font-size:18px; color:#6b4b34;">
                                            Servicios incluidos
                                        </h3>

                                        @foreach($cita->detalles as $detalle)
                                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:12px; background:#fffdfa; border:1px solid #e2d4c4; border-radius:12px;">
                                                <tr>
                                                    <td style="padding:14px 16px; font-size:14px; color:#6b4b34;">
                                                        {{ $detalle->tipoServicio->nombre ?? $detalle->tipoServicio->nom ?? 'Servicio' }}
                                                    </td>
                                                    <td align="right" style="padding:14px 16px; font-size:14px; color:#6b4b34;">
                                                        ${{ number_format($detalle->subtotal, 2) }}
                                                    </td>
                                                </tr>
                                            </table>
                                        @endforeach

                                        <div style="text-align:center; margin:24px 0 22px 0;">
                                            <a href="{{ $googleCalendarUrl }}"
                                               target="_blank"
                                               style="display:inline-block; background:#7a5537; color:#ffffff; text-decoration:none; padding:14px 22px; border-radius:12px; font-size:15px; font-weight:bold;">
                                                Agregar a Google Calendar
                                            </a>
                                        </div>

                                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#eee1c6; border-radius:12px;">
                                            <tr>
                                                <td style="padding:16px 18px; font-size:13px; line-height:1.7; color:#6b4b34;">
                                                    Gracias por confiar en Estética Nova. Te esperamos para consentirte y hacerte vivir una experiencia increíble ✨
                                                </td>
                                            </tr>
                                        </table>

                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:0 20px 8px 20px;">
                            <p style="margin:0; font-size:12px; color:#fff;">
                                Estética Nova · Belleza & Bienestar
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>