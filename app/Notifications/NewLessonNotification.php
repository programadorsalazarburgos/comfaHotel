<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewLessonNotification extends Notification
{
    use Queueable;
    protected $lesson;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($lesson)
    {
        $this->lesson=$lesson;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }


 public function toDatabase($notifiable)
    {
       return [
            'lesson' => $this->lesson,
        ];
    }

 // public function toBroadcast($notifiable)
 //    {
 //      return [
 //            'lesson' => $this->lesson,
 //        ];

 //        return new BroadcastMessage([

 //            'lesson' => $this->lesson,
    
 //        ]);

       
 //    }



    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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
            //
        ];
    }
}
