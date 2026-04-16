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
        ->line('Primero verifica tu correo haciendo clic aquí:')
        ->action('Verificar Email', $url)         
        ->line('Una vez verificado, ya puedes iniciar sesión:')
        ->action('Ir al Login', 'https://carlosd-dev.me/login')   
        ->line('Si tú no creaste esta cuenta, puedes ignorar este correo.');
}
}