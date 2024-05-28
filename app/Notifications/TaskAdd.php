<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Broadcasting\PrivateChannel;


class TaskAdd extends Notification implements ShouldQueue
{
    use Queueable;

    public $task;
    public $user;
    
    /**
     * Create a new notification instance.
     *
     * @param $task
     */
    public function __construct($task,$user)
    {
       $this->task = $task;
       $this->user = $user;
      
      
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['broadcast', 'mail'];
    }

    /**
     * Get the broadcast representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
     
            'Task' => [
                'Task_ID' => $this->task->task_id,
                'Task' => $this->task->task,
                'Description' => $this->task->description,   
             ],
             'Assigned User' => $this->user->id,
        ]);
    }


    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('New Task Added')
                    ->line('A new task has been added.')
                    ->action('View Task', url('http://127.0.0.1:8000/api/tasks/'.$this->task->task_id))
                    ->line('Thank you for using our application!');
    }

    public function broadcastOn()
    {
        return new PrivateChannel('taskchannel.' . $this->user->id);
    }

    



    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    // public function toArray(object $notifiable): array
    // {
    //     return [
    //         'message' => 'Hello Bruv!'
    //     ];
    // }
}
