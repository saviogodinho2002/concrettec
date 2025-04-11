<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

class CustomResetPassword extends Notification
{
    use Queueable;

    public $email;
    public $message;
    public $sub_message;

    public function __construct($token, $email,$message = 'Você está recebendo este e-mail porque recebemos uma solicitação de redefinição de senha para sua conta.', $sub_message = 'Se você não solicitou uma redefinição de senha, nenhuma ação é necessária.' )
    {
        $this->token = $token;
        $this->email = $email;
        $this->message = $message;
        $this->sub_message = $sub_message;
    }
    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $resetUrl = $this->resetUrl($notifiable);

        return (new MailMessage)
            ->subject('Redefinição de Senha')
            ->greeting('Olá!')
            ->line($this->message)
            ->action('Redefinir Senha', $resetUrl)
            ->line('Este link expirará em ' . \Config::get('auth.passwords.'.config('auth.defaults.passwords').'.expire') . ' minutos.')
            ->line($this->sub_message)
            ->salutation('Atenciosamente, Equipe Concrettec');
    }
    protected function resetUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'password.reset',
            Carbon::now()->addMinutes(Config::get('auth.passwords.'.config('auth.defaults.passwords').'.expire')),
            ['token' => $this->token, 'email' => $this->email]
        );
    }
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
