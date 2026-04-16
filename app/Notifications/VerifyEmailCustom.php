<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailCustom extends VerifyEmail
{
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject('Bienvenido a Estética Nova')
            ->greeting('¡Hola!')
            ->line('Gracias por registrarte en Estética Nova.')
            ->line('Ya puedes iniciar sesión desde el siguiente botón.')
            ->action('Ir al login', 'https://carlosd-dev.me/login')
            ->line('Si tú no creaste esta cuenta, puedes ignorar este correo.');
    }
}