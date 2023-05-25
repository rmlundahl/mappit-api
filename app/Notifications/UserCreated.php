<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App;

class UserCreated extends Notification
{
    use Queueable;

    private $name;
    private $email;
    private $password;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($name, $email, $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject(__('user.welcome'))
                    ->greeting(__('user.welcome_name', ['name' => $this->name]))
                    ->line(__('user.welcome_line_1'))
                    ->line(__('user.welcome_email', ['email' => $this->email]))
                    ->line(__('user.welcome_password', ['password' => $this->password]))
                    ->action(__('user.welcome_action'), url('/'.App::getLocale().'/login'));
                    
    }

}
