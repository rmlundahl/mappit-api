<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App, Log;

class ItemPublished extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->item = $item;

        // use translations from the package
        $this->ns = env('APP_NAMESPACE');
        $this->item_type = __($this->ns.'::item_type.'.$this->item->item_type_id);
        $this->url = config('mappit.app_url_frontend').'/'.App::getLocale().'/admin/'.__($this->ns.'::item_type.slug_'.$this->item->item_type_id).'/'.$this->item->id.'/edit?item_type_id='.$this->item->item_type_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
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
                    ->subject(__($this->ns.'::notification.item_published_subject', ['item_type'=>$this->item_type, 'item_id'=>$this->item->id]))
                    ->greeting(__($this->ns.'::notification.item_published_greeting', ['item_type'=>$this->item_type, 'item_id'=>$this->item->id]))
                    ->line(__($this->ns.'::notification.item_published_line_1', ['item_type'=>$this->item_type, 'item_id'=>$this->item->id]))
                    ->line(__($this->ns.'::notification.item_published_line_2', ['item_type'=>$this->item_type, 'item_id'=>$this->item->id]))
                    ->action(__($this->ns.'::notification.item_published_action', ['item_type'=>$this->item_type]), $this->url);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'item_id'   => $this->item->id,
            'item_type' => $this->item_type,
            'subject'   => __($this->ns.'::notification.item_published_subject', ['item_type'=>$this->item_type, 'item_id'=>$this->item->id]),
            'greeting'  => __($this->ns.'::notification.item_published_greeting', ['item_type'=>$this->item_type, 'item_id'=>$this->item->id]),
            'line_1'    => __($this->ns.'::notification.item_published_line_1', ['item_type'=>$this->item_type, 'item_id'=>$this->item->id]),
            'line_2'    => __($this->ns.'::notification.item_published_line_2', ['item_type'=>$this->item_type, 'item_id'=>$this->item->id]),
            'action'    => __($this->ns.'::notification.item_published_action', ['item_type'=>$this->item_type]), 
            'url'       => $this->url
        ];
    }
    
}
