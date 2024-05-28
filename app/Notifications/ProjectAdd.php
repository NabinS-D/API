<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use App\Models\Project;
use Illuminate\Broadcasting\PrivateChannel;

class ProjectAdd extends Notification implements ShouldQueue
{
    use Queueable;

    public $project;
    public $user;

    /**
     * Create a new notification instance.
     *
     * @param $project
     */
    public function __construct(Project $project, $user)
    {
        $this->project = $project;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast'];
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
            'project' => [
                'project_name' => $this->project->project_name, // Accessing project_name using array notation
                'description' => $this->project->description, // Accessing description using array notation
                'project_id' => $this->project->project_id, // Accessing project_id using array notation
            ]
        ]);
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->user->id);
    }
}
