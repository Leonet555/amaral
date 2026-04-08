<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            $minutes = (int) config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60);

            return (new MailMessage)
                ->subject('Redefinir sua senha — Vitorum')
                ->line('Você recebeu este e-mail porque foi solicitada a redefinição de senha da sua conta na Vitorum.')
                ->action('Redefinir senha', $url)
                ->line("Este link expira em {$minutes} minutos.")
                ->line('Se você não fez este pedido, ignore este e-mail.');
        });
    }
}
