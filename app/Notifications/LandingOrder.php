<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LandingOrder extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    private $order_id;
    public function __construct($order_id)
    {
        $this->order_id = $order_id;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line(' لديك طلب جديد علي الموقع الالكتروني من صفحة الهبوط ')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id'=>$this->order_id,
            'title'=>' طلب جديد علي الموقع الالكتروني  من صغحات الهبوط ::  ',
        ];
    }
}
