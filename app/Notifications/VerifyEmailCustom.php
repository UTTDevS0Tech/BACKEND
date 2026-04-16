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
        ->action('Verificar Email', $url)         
        ->line('Ya puedes iniciar sesión:')
        ->action('Acceder a mi cuenta', 'https://carlosd-dev.me/login')   
        ->line('Si tú no creaste esta cuenta, puedes ignorar este correo.');
}
}