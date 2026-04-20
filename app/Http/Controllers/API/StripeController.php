<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\TipoServicio;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;


class StripeController extends Controller
{

    use apiResponse;
    public function createPaymentIntent(Request $request)
    {
    Stripe::setApiKey(config('services.stripe.secret'));

    try {
            $detalleCita = $request->input('detalle_cita');//se saca lo q viene de la store pinia

            if (empty($detalleCita)) {
                return $this->apiResponse(null, 'No hay servicios seleccionados', 400);
            }
            $ids = collect($detalleCita)->pluck('tipo_servicio_id');

            //  Buscamos los precios reales en esequele server y los sumamos
            // esto evita que alguien truque el precio desde el navegador
            $totalServicios = TipoServicio::whereIn('id', $ids)->sum('precio');

            if ($totalServicios <= 0) {
                return $this->apiResponse(null, 'Error al calcular el costo de los servicios', 400);
            }

            //  calculamos el apartado
            $montoApartado = $totalServicios * 0.20;

            //  convertimos a centavos stripe no acepta puntos decimales
            // eje $150.50 MXN = 15050
            $montoCentavos = (int) round($montoApartado * 100);

            // 7. Creamos el intento de pago en los servidores de Stripe //esto es logica de stripe que s iempre s eitene que usar
            $paymentIntent = PaymentIntent::create([
                'amount' => $montoCentavos,
                'currency' => 'mxn',
                'description' => 'Apartado de Cita - Estética Yamileth',
                'metadata' => [
                    'cliente_id' => Auth::id() ?? 'invitado',
                    'servicios' => $ids->implode(','), // Guardamos los ides para saber que pagan
                    'total_servicios' => $totalServicios
                ],
                'automatic_payment_methods' => ['enabled' => true],
            ]);

            //  le regresamos el clientSecret a tu vue tambien es logica del straip
            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
                'total_a_pagar' => $montoApartado, // Para que lo vea el cliente en la pantalla
                'moneda' => 'MXN'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al procesar el intento de pago',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }
}
