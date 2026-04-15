<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmación de cita</title>
</head>
<body style="margin:0; padding:0; background-color:#f6f1df; font-family: Arial, Helvetica, sans-serif; color:#6b4f3b;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f6f1df; margin:0; padding:30px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:700px; background:linear-gradient(135deg, #dfe8c7 0%, #ecd0ad 100%); border-radius:24px; overflow:hidden; box-shadow:0 8px 30px rgba(107,79,59,0.10);">
                    
                    {{-- Header --}}
                    <tr>
                        <td style="padding:32px 36px 10px 36px;">
                            <table role="presentation" width="100%">
                                <tr>
                                    <td align="left">
                                        <img 
                                            src="{{ asset('images/logo-nova.png') }}" 
                                            alt="Estética Nova" 
                                            style="max-width:180px; height:auto; display:block;"
                                        >
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Hero --}}
                    <tr>
                        <td style="padding:10px 36px 0 36px;">
                            <div style="display:inline-block; background-color:rgba(255,255,255,0.35); color:#fff; font-size:14px; font-weight:bold; padding:10px 18px; border-radius:999px;">
                                Belleza &amp; Bienestar
                            </div>

                            <h1 style="margin:24px 0 14px 0; font-size:48px; line-height:1.05; color:#fff; font-weight:800;">
                                Tu cita ha sido<br>agendada
                            </h1>

                            <p style="margin:0; font-size:22px; line-height:1.6; color:#fff;">
                                Hola <strong>{{ $cita->cliente->nom ?? 'cliente' }} {{ $cita->cliente->apellido_p ?? '' }} {{ $cita->cliente->apellido_m ?? '' }}</strong>,
                                tu reservación quedó confirmada correctamente.
                            </p>
                        </td>
                    </tr>

                    {{-- Card principal --}}
                    <tr>
                        <td style="padding:30px 36px 36px 36px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f8f4e8; border-radius:22px; box-shadow:0 6px 18px rgba(107,79,59,0.08);">
                                <tr>
                                    <td style="padding:28px;">

                                        <h2 style="margin:0 0 20px 0; font-size:28px; color:#6b4f3b;">
                                            Detalles de tu cita
                                        </h2>

                                        {{-- Info principal --}}
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding:12px 0; border-bottom:1px solid #e6dcc6; font-size:16px; color:#8b6a50; width:34%;">
                                                    <strong>Fecha</strong>
                                                </td>
                                                <td style="padding:12px 0; border-bottom:1px solid #e6dcc6; font-size:16px; color:#6b4f3b;">
                                                    {{ $cita->fecha_c }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="padding:12px 0; border-bottom:1px solid #e6dcc6; font-size:16px; color:#8b6a50;">
                                                    <strong>Hora de inicio</strong>
                                                </td>
                                                <td style="padding:12px 0; border-bottom:1px solid #e6dcc6; font-size:16px; color:#6b4f3b;">
                                                    {{ $cita->hora_c }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="padding:12px 0; border-bottom:1px solid #e6dcc6; font-size:16px; color:#8b6a50;">
                                                    <strong>Hora de fin</strong>
                                                </td>
                                                <td style="padding:12px 0; border-bottom:1px solid #e6dcc6; font-size:16px; color:#6b4f3b;">
                                                    {{ $cita->hora_fin }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="padding:12px 0; border-bottom:1px solid #e6dcc6; font-size:16px; color:#8b6a50;">
                                                    <strong>Te atiende</strong>
                                                </td>
                                                <td style="padding:12px 0; border-bottom:1px solid #e6dcc6; font-size:16px; color:#6b4f3b;">
                                                    {{ $cita->personal->nombre ?? 'Sin asignar' }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="padding:12px 0; font-size:16px; color:#8b6a50;">
                                                    <strong>Total</strong>
                                                </td>
                                                <td style="padding:12px 0; font-size:18px; color:#c7925d; font-weight:bold;">
                                                    ${{ number_format($cita->total ?? 0, 2) }}
                                                </td>
                                            </tr>
                                        </table>

                                        {{-- Servicios --}}
                                        <h3 style="margin:30px 0 16px 0; font-size:22px; color:#6b4f3b;">
                                            Servicios incluidos
                                        </h3>

                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#fffdf8; border:1px solid #eadfca; border-radius:16px;">
                                            @forelse($cita->detalles as $detalle)
                                                <tr>
                                                    <td style="padding:14px 16px; border-bottom:1px solid #efe6d4; font-size:16px; color:#6b4f3b;">
                                                        {{ $detalle->tipoServicio->nom ?? 'Servicio' }}
                                                    </td>
                                                    <td align="right" style="padding:14px 16px; border-bottom:1px solid #efe6d4; font-size:16px; color:#8b6a50; font-weight:bold;">
                                                        ${{ number_format($detalle->precio_capturado ?? 0, 2) }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="2" style="padding:16px; font-size:15px; color:#8b6a50;">
                                                        No hay servicios registrados en esta cita.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </table>

                                        {{-- Mensaje final --}}
                                        <div style="margin-top:28px; padding:18px 20px; background:#efe3c8; border-radius:16px; color:#6b4f3b; font-size:15px; line-height:1.7;">
                                            Gracias por confiar en <strong>Estética Nova</strong>.  
                                            Te esperamos para consentirte y hacerte vivir una experiencia increíble ✨
                                        </div>

                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td align="center" style="padding:0 36px 32px 36px; color:#fff; font-size:13px; line-height:1.6;">
                            Estética Nova · Belleza &amp; Bienestar
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>